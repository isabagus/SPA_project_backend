<?php

namespace App\Services;

use App\Models\ReportDetail;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Reports;
use App\Models\RubricCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class TeacherService
{
    /**
     * Mengambil daftar subject yang diampu teacher
     * Dioptimalkan dengan Eager Loading dan relasi whereHas
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
     * Mengambil daftar siswa beserta average nilai mereka
     * Menggunakan metode Eager Loading untuk menghilangkan "N+1 Query Problem"
     */
    public function getStudentsWithScore(Teacher $teacher, int $subjectId): array
    {
        // Pastikan teacher benar-benar mengampu subject ini
        $subject = Subject::whereHas('rubrics', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->teacher_id);
            })->find($subjectId);

        if (!$subject) {
            return ['authorized' => false, 'subject' => null, 'students' => []];
        }

        // Ambil data siswa sekaligus dengan nilai raport mereka di subject ini secara PARALEL (Eager Load)
        $students = Student::where('level_class', $subject->level_class)
            ->with(['reports' => function ($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            }])
            ->orderBy('name_student')
            ->get()
            ->map(function (Student $student) {
                // Ekstrak data raport dari hasil Eager Loading
                $report = $student->reports->first();

                $student->report_id     = $report?->report_id;
                $student->average_value = $report?->average_value;
                $student->has_score     = !is_null($report?->average_value);

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
     * Mengambil form penilaian rubrik untuk 1 siswa di 1 subject
     */
    public function getScoreForm(Teacher $teacher, int $subjectId, int $studentId): array
    {
        $subject = Subject::find($subjectId);
        $student = Student::find($studentId);

        if (!$subject || !$student) {
            return ['authorized' => false];
        }

        $rubrics = RubricCategory::where('teacher_id', $teacher->teacher_id)
            ->where('subject_id', $subjectId)
            ->get();

        if ($rubrics->isEmpty()) {
            return ['authorized' => false];
        }

        // Eager load detail nilai agar tidak melakukan query berulang
        $report = Reports::with('reportDetails')
            ->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->first();

        // Konversi ke Collection Key-Value yang lebih cepat dibaca oleh CPU
        $details = $report ? $report->reportDetails->keyBy('rubric_id') : collect();

        // Arrow function (PHP 7.4+) map untuk penulisan yang ultra-bersih
        $mappedRubrics = $rubrics->map(fn($rubric) => [
            'rubric_id'           => $rubric->rubric_id,
            'rubric_name'         => $rubric->rubric_name,
            'current_score'       => $details->has($rubric->rubric_id) ? (float) $details->get($rubric->rubric_id)->score : null,
            'description_subject' => $details->has($rubric->rubric_id) ? $details->get($rubric->rubric_id)->description_subject : '',
        ]);

        return [
            'authorized'    => true,
            'student'       => $student,
            'subject'       => $subject,
            'report_id'     => $report?->report_id,
            'average_value' => $report?->average_value,
            'rubrics'       => $mappedRubrics,
        ];
    }

    /**
     * Menyimpan/mengupdate nilai siswa per rubrik dan hitung rata-rata
     */
    public function submitScore(Teacher $teacher, int $subjectId, int $studentId, array $data): ?Reports
    {
        $subject = Subject::find($subjectId);
        $student = Student::find($studentId);

        if (!$subject || !$student || $student->level_class !== $subject->level_class) {
            return null;
        }

        // Cache array ID rubric milik guru ini sebagai keamanan tambahan
        $teacherRubricIds = RubricCategory::where('teacher_id', $teacher->teacher_id)
            ->where('subject_id', $subjectId)
            ->pluck('rubric_id');

        if ($teacherRubricIds->isEmpty()) {
            return null;
        }

        // Gunakan Transaction block murni dari DB Facades
        return DB::transaction(function () use ($subjectId, $studentId, $subject, $data, $teacherRubricIds) {
            $report = Reports::firstOrCreate(
                ['student_id' => $studentId, 'subject_id' => $subjectId],
                [
                    'academic_year' => $data['academic_year'],
                    'level_class'   => $subject->level_class,
                    'average_value' => 0,
                    'attendance'    => 0,
                    'mentor_note'   => null,
                ]
            );

            // Filter out malicious rubrics if any, using Collection functional filtering
            $validScores = collect($data['scores'])->filter(fn($score) => $teacherRubricIds->contains($score['rubric_id']));

            // Lakukan Insert/Update tanpa menggunakan updateOrCreate (karena tabel details_report tidak memiliki primary key 'id')
            $validScores->each(function ($scoreData) use ($report) {
                $match = [
                    'report_id' => $report->report_id,
                    'rubric_id' => $scoreData['rubric_id']
                ];
                $dataToSave = [
                    'score'               => $scoreData['score'],
                    'description_subject' => $scoreData['description_subject'] ?? '-'
                ];

                $existingDetail = ReportDetail::where($match)->first();

                if ($existingDetail) {
                    // Update menggunakan Query Builder untuk menghindari error missing 'id' column
                    ReportDetail::where($match)->update($dataToSave);
                } else {
                    ReportDetail::create(array_merge($match, $dataToSave));
                }
            });

            // Rekalkulasi average langsung dari aggregate sum SQL (jauh lebih hemat memori dibanding menarik semua row)
            $average = ReportDetail::where('report_id', $report->report_id)->avg('score');
            
            // Simpan average baru
            $report->update(['average_value' => $average ? round($average, 2) : 0]);

            return $report;
        });
    }
}
