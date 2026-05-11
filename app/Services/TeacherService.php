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
        return Subject::where('teacher_id', $teacher->teacher_id)
            ->with('rubrics')
            ->orderBy('level_class')
            ->orderBy('term')
            ->get();
    }

    /**
     * Mengambil daftar siswa beserta average nilai mereka
     */
    public function getStudentsWithScore(Teacher $teacher, int $subjectId): array
    {
        $subject = Subject::where('subject_id', $subjectId)
            ->where('teacher_id', $teacher->teacher_id)
            ->first();

        if (!$subject) {
            return ['authorized' => false, 'subject' => null, 'students' => []];
        }

        $students = Student::where('level_class', $subject->level_class)
            ->with(['reports' => function ($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            }])
            ->orderBy('name_student')
            ->get()
            ->map(function (Student $student) {
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
     * Mengambil form penilaian rubrik (dengan Sub-Kriteria)
     */
    public function getScoreForm(Teacher $teacher, int $subjectId, int $studentId): array
    {
        $subject = Subject::find($subjectId);
        $student = Student::find($studentId);

        if (!$subject || !$student) {
            return ['authorized' => false];
        }

        // Ambil kategori rubrik beserta sub-kriterianya (Parent-Child)
        $rubrics = RubricCategory::with('criteria')
            ->where('teacher_id', $teacher->teacher_id)
            ->where('subject_id', $subjectId)
            ->get();

        if ($rubrics->isEmpty()) {
            return ['authorized' => false];
        }

        // Ambil detail nilai yang sudah ada (Key by criteria_id)
        $report = Reports::with('reportDetails')
            ->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->first();

        $details = $report ? $report->reportDetails->keyBy('criteria_id') : collect();

        // Map data rubrik ke struktur yang diinginkan frontend
        $mappedRubrics = $rubrics->map(fn($rubric) => [
            'rubric_id'   => $rubric->rubric_id,
            'rubric_name' => $rubric->rubric_name,
            'criteria'    => $rubric->criteria->map(fn($c) => [
                'criteria_id'         => $c->criteria_id,
                'criteria_name'       => $c->criteria_name,
                'current_score'       => $details->has($c->criteria_id) ? (float) $details->get($c->criteria_id)->score : null,
                'description_subject' => $details->has($c->criteria_id) ? $details->get($c->criteria_id)->description_subject : ($c->default_description ?? ''),
            ])
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
     * Menyimpan/mengupdate nilai siswa per kriteria
     */
    public function submitScore(Teacher $teacher, int $subjectId, int $studentId, array $data): ?Reports
    {
        $subject = Subject::find($subjectId);
        $student = Student::find($studentId);

        if (!$subject || !$student) {
            return null;
        }

        // Ambil semua kriteria valid yang dimiliki teacher untuk subject ini
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

            // Cache mapping criteria_id => rubric_id untuk menghindari N+1 query di dalam loop
            $criteriaToRubricMap = RubricCriteria::whereIn('criteria_id', collect($data['scores'])->pluck('criteria_id'))
                ->pluck('rubric_id', 'criteria_id');

            // Simpan nilai per kriteria
            foreach ($data['scores'] as $scoreData) {
                $cId = $scoreData['criteria_id'];
                if (!$validCriteriaIds->contains($cId)) continue;

                $match = [
                    'report_id'   => $report->report_id,
                    'criteria_id' => $cId
                ];

                $updateData = [
                    'score'               => $scoreData['score'],
                    'description_subject' => $scoreData['description_subject'] ?? '-',
                    'rubric_id'           => $criteriaToRubricMap[$cId] ?? null
                ];

                ReportDetail::updateOrCreate($match, $updateData);
            }

            // Rekalkulasi rata-rata (AVG dari semua criteria yang diisi)
            $average = ReportDetail::where('report_id', $report->report_id)->avg('score');
            $report->update(['average_value' => $average ? round($average, 2) : 0]);

            return $report;
        });
    }
}
