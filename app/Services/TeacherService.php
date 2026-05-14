<?php

namespace App\Services;

use App\Models\ReportDetail;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Reports;
use App\Models\RubricCategory;
use App\Models\RubricCriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class TeacherService
{
    /**
     * Mengambil daftar subject yang diampu teacher
     */
    public function getMySubjects(Teacher $teacher): Collection
    {
        return Subject::whereHas('rubrics', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->teacher_id);
        })
        ->with(['rubrics' => function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->teacher_id);
        }])
        ->orderBy('level_class')
        ->orderBy('term')
        ->get();
    }

    /**
     * Mengambil daftar siswa beserta average nilai dan status kelengkapan
     */
    public function getStudentsWithScore(Teacher $teacher, int $subjectId): array
    {
        $subject = Subject::where('subject_id', $subjectId)
            ->whereHas('rubrics', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->teacher_id);
            })
            ->first();

        if (!$subject) {
            return ['authorized' => false, 'subject' => null, 'students' => []];
        }

        // Hitung total kriteria yang seharusnya dinilai untuk subjek ini
        $totalCriteriaCount = RubricCriteria::whereHas('category', function($q) use ($teacher, $subjectId) {
            $q->where('teacher_id', $teacher->teacher_id)->where('subject_id', $subjectId);
        })->count();

        $students = Student::where('class_id', $subject->class_id)
            ->with(['reports' => function ($query) use ($subjectId) {
                $query->where('subject_id', $subjectId)->withCount('reportDetails');
            }])
            ->orderBy('name_student')
            ->get()
            ->map(function (Student $student) use ($totalCriteriaCount) {
                $report = $student->reports->first();
                $filledCriteriaCount = $report ? (int) $report->report_details_count : 0;

                // LOGIKA STATUS DINAMIS (On-the-fly)
                $status = 'none';
                if ($totalCriteriaCount > 0) {
                    if ($filledCriteriaCount === 0) {
                        $status = 'none';
                    } elseif ($filledCriteriaCount < $totalCriteriaCount) {
                        $status = 'draft';
                    } else {
                        $status = 'completed';
                    }
                }

                $student->report_id     = $report?->report_id;
                $student->average_value = $report?->average_value;
                $student->status_score  = $status; // 'completed', 'draft', 'none'
                $student->completion    = $totalCriteriaCount > 0 ? round(($filledCriteriaCount / $totalCriteriaCount) * 100) : 0;
                
                unset($student->reports);
                return $student;
            });

        return [
            'authorized' => true,
            'subject'    => $subject,
            'students'   => $students,
        ];
    }

    /**
     * Mengambil form penilaian rubrik (dengan Sub-Kriteria & Collaborative Support)
     */
    public function getScoreForm(Teacher $teacher, int $subjectId, int $studentId): array
    {
        $subject = Subject::find($subjectId);
        $student = Student::find($studentId);

        $isReadOnlyMode = false;
        $reason = null;

        // --- VALIDASI OTORITAS AGAMA (BENANG MERAH) ---
        if (str_starts_with($subject->category_subject, 'Religion')) {
            $studentRel = strtolower($student->religion_name);
            $subjectCat = strtolower($subject->category_subject);

            if (!str_contains($subjectCat, $studentRel)) {
                $isReadOnlyMode = true;
                $reason = "Halaman ini bersifat Read-Only karena agama siswa ({$student->religion_name}) berbeda dengan kompetensi mapel Anda.";
            }
        }

        // 1. Tentukan subjek-subjek mana saja yang masuk dalam grup kolaborasi
        $targetSubjectIds = [$subjectId]; // Default to current subject
        
        if ($subject->report_group_key) {
            $groupSubjectIds = Subject::where('report_group_key', $subject->report_group_key)
                ->where('level_class', $subject->level_class)
                ->where('term', $subject->term)
                ->pluck('subject_id')
                ->toArray();
            
            if (!empty($groupSubjectIds)) {
                $targetSubjectIds = $groupSubjectIds;
            }
        }

        $report = Reports::with('reportDetails')
            ->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->first();

        // 2. Ambil semua rubrik dari grup tersebut
        // Urutkan agar kriteria milik saya (teacher_id login) muncul paling atas
        $allGroupRubrics = RubricCategory::with(['criteria', 'subject'])
            ->whereIn('subject_id', $targetSubjectIds)
            ->get();

        // 3. Filter Rubrik: Hanya tampilkan PKN dan Agama yang relevan dengan siswa
        $studentReligion = strtolower($student->religion_name);
        
        $filteredRubrics = $allGroupRubrics->filter(function($rubric) use ($studentReligion) {
            $catName = strtolower($rubric->subject->category_subject);
            
            // Jika ini kategori Agama, cek apakah cocok dengan agama siswa
            if (str_contains($catName, 'religion')) {
                return str_contains($catName, $studentReligion);
            }
            
            // Selain agama (seperti PKN), tampilkan semua
            return true;
        });

        // Ambil details dari SEMUA subjek dalam grup untuk ditampilkan
        $allReportIds = Reports::where('student_id', $studentId)
            ->whereIn('subject_id', $targetSubjectIds)
            ->pluck('report_id');

        $details = ReportDetail::whereIn('report_id', $allReportIds)->get()->keyBy('criteria_id');

        $mappedRubrics = $filteredRubrics->map(fn($rubric) => [
            'rubric_id'   => $rubric->rubric_id,
            'rubric_name' => $rubric->rubric_name,
            'is_mine'     => $isReadOnlyMode ? false : ($rubric->teacher_id === $teacher->teacher_id),
            'criteria'    => $rubric->criteria->map(fn($c) => [
                'criteria_id'         => $c->criteria_id,
                'criteria_name'       => $c->criteria_name,
                'is_mine'             => $isReadOnlyMode ? false : ($rubric->teacher_id === $teacher->teacher_id),
                'current_score'       => $details->has($c->criteria_id) ? (float) $details->get($c->criteria_id)->score : null,
                'description_subject' => $details->has($c->criteria_id) ? $details->get($c->criteria_id)->description_subject : ($c->default_description ?? ''),
            ])
        ])->sortByDesc('is_mine')->values();

        return [
            'authorized'         => true,
            'is_read_only_mode'  => $isReadOnlyMode,
            'read_only_reason'   => $reason,
            'student'            => $student,
            'subject'            => $subject,
            'report_id'          => $report?->report_id,
            'average_value'      => $report?->average_value,
            'rubrics'            => $mappedRubrics,
        ];
    }

    /**
     * Menyimpan/mengupdate nilai siswa per kriteria
     */
    public function submitScore(Teacher $teacher, int $subjectId, int $studentId, array $data): ?Reports
    {
        $subject = Subject::find($subjectId);
        $student = Student::find($studentId);

        if (!$subject || !$student) {
            return null;
        }

        // --- ENFORCE SECURITY: VALIDASI AGAMA ---
        if (str_starts_with($subject->category_subject, 'Religion')) {
            if (!str_contains(strtolower($subject->category_subject), strtolower($student->religion_name))) {
                throw new \Exception("Unauthorized religious assessment.");
            }
        }

        $validCriteriaIds = RubricCriteria::whereHas('category', function($q) use ($teacher, $subjectId) {
            $q->where('teacher_id', $teacher->teacher_id)->where('subject_id', $subjectId);
        })->pluck('criteria_id');

        if ($validCriteriaIds->isEmpty()) {
            return null;
        }

        return DB::transaction(function () use ($subjectId, $studentId, $subject, $data, $validCriteriaIds) {
            $report = Reports::firstOrCreate(
                ['student_id' => $studentId, 'subject_id' => $subjectId],
                [
                    'academic_year' => $data['academic_year'] ?? '2024/2025',
                    'level_class'   => $subject->level_class,
                    'average_value' => 0,
                    'attendance'    => 0,
                ]
            );

            $criteriaToRubricMap = RubricCriteria::whereIn('criteria_id', collect($data['scores'])->pluck('criteria_id'))
                ->pluck('rubric_id', 'criteria_id');

            foreach ($data['scores'] as $scoreData) {
                $cId = $scoreData['criteria_id'];
                if (!$validCriteriaIds->contains($cId)) continue;

                $match = [
                    'report_id'   => $report->report_id,
                    'criteria_id' => $cId
                ];

                // Jika score kosong, kita hapus recordnya (Draft Mode)
                if (is_null($scoreData['score']) || $scoreData['score'] === '') {
                    ReportDetail::where($match)->delete();
                    continue;
                }

                $updateData = [
                    'score'               => $scoreData['score'],
                    'description_subject' => $scoreData['description_subject'] ?? '-',
                    'rubric_id'           => $criteriaToRubricMap[$cId] ?? null
                ];

                ReportDetail::updateOrCreate($match, $updateData);
            }

            $average = ReportDetail::where('report_id', $report->report_id)->avg('score');
            $report->update(['average_value' => $average ? round($average, 2) : 0]);

            return $report;
        });
    }

    /**
     * Rubric Management Logic
     */
    public function getMyRubrics(Teacher $teacher, int $subjectId): Collection
    {
        return RubricCategory::with('criteria')
            ->where('teacher_id', $teacher->teacher_id)
            ->where('subject_id', $subjectId)
            ->get();
    }

    public function storeCategory(Teacher $teacher, int $subjectId, array $data): RubricCategory
    {
        $subject = Subject::find($subjectId);
        return RubricCategory::create([
            'teacher_id'  => $teacher->teacher_id,
            'subject_id'  => $subjectId,
            'rubric_name' => $data['rubric_name'],
            'term'        => $subject->term,
        ]);
    }

    public function updateCategory(Teacher $teacher, int $rubricId, array $data): bool
    {
        return RubricCategory::where('rubric_id', $rubricId)
            ->where('teacher_id', $teacher->teacher_id)
            ->update(['rubric_name' => $data['rubric_name']]);
    }

    public function destroyCategory(Teacher $teacher, int $rubricId): bool
    {
        return RubricCategory::where('rubric_id', $rubricId)
            ->where('teacher_id', $teacher->teacher_id)
            ->delete();
    }

    public function storeCriteria(Teacher $teacher, int $rubricId, array $data): RubricCriteria
    {
        $category = RubricCategory::where('rubric_id', $rubricId)
            ->where('teacher_id', $teacher->teacher_id)
            ->firstOrFail();

        return RubricCriteria::create([
            'rubric_id'           => $rubricId,
            'criteria_name'       => $data['criteria_name'],
            'default_description' => $data['default_description'] ?? null,
        ]);
    }

    public function updateCriteria(Teacher $teacher, int $criteriaId, array $data): bool
    {
        $criteria = RubricCriteria::where('criteria_id', $criteriaId)
            ->whereHas('category', fn($q) => $q->where('teacher_id', $teacher->teacher_id))
            ->firstOrFail();

        return $criteria->update([
            'criteria_name'       => $data['criteria_name'],
            'default_description' => $data['default_description'] ?? null,
        ]);
    }

    public function destroyCriteria(Teacher $teacher, int $criteriaId): bool
    {
        $criteria = RubricCriteria::where('criteria_id', $criteriaId)
            ->whereHas('category', fn($q) => $q->where('teacher_id', $teacher->teacher_id))
            ->firstOrFail();

        return $criteria->delete();
    }
}
