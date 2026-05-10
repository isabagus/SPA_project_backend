<?php 
namespace App\Services;

use App\Models\ReportDetail;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Reports;
use App\Models\User;
use App\Models\RubricCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class TeacherService
{
    /**
     * Mengambil daftar subject yang diampu teacher
     */
    public function getMySubjects(Teacher $teacher)
    {
        $subjectId = RubricCategory::where('teacher_id', $teacher->teacher_id)->pluck('subject_id')->unique();

        return Subject::with([
            'rubrics' => function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->teacher_id);
            }
        ])->whereIn('subject_id', $subjectId)->orderBy('level_class')->orderBy('term')->get();
    }

    /**
     * Mengambil daftar siswa beserta average nilai mereka
     */
    public function getStudentsWithScore(Teacher $teacher, int $subjectId)
    {
        $subject = Subject::find($subjectId);

        if (!$subject) {
            return ['authorized' => false, 'subject' => null, 'students' => null];
        }

        $isAuthorized = RubricCategory::where('teacher_id', $teacher->teacher_id)->where('subject_id', $subjectId)->exists();
        if (!$isAuthorized) {
            return ['authorized' => false, 'subject' => null, 'students' => null];
        }

        $students = Student::where('level_class', $subject->level_class)->orderBy('name_student')->get();
        $studentId = $students->pluck('student_id');
        $reports = Reports::where('subject_id', $subjectId)->whereIn('student_id', $studentId)->get()->keyBy('student_id');
 
        $students = $students->map(function (Student $student) use ($reports) {
            $report = $reports->get($student->student_id);
            $student->report_id     = $report?->report_id;
            $student->average_value = $report?->average_value;
            $student->has_score     = !is_null($report?->average_value);
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

        // Ambil semua rubrik teacher di subject ini
        $rubrics = RubricCategory::where('teacher_id', $teacher->teacher_id)
            ->where('subject_id', $subjectId)
            ->get();

        if ($rubrics->isEmpty()) {
            return ['authorized' => false];
        }

        // Cari report yang ada
        $report = Reports::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->first();

        $details = collect();
        if ($report) {
            $details = ReportDetail::where('report_id', $report->report_id)
                ->get()
                ->keyBy('rubric_id');
        }

        // Mapping rubrik dengan nilai yang sudah ada (jika ada)
        $mappedRubrics = $rubrics->map(function ($rubric) use ($details) {
            $detail = $details->get($rubric->rubric_id);
            return [
                'rubric_id'           => $rubric->rubric_id,
                'rubric_name'         => $rubric->rubric_name,
                'current_score'       => $detail ? (float) $detail->score : null,
                'description_subject' => $detail ? $detail->description_subject : '',
            ];
        });

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

        return DB::transaction(function () use ($teacher, $subjectId, $studentId, $subject, $data) {
            $report = Reports::firstOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                ],
                [
                    'academic_year' => $data['academic_year'],
                    'level_class'   => $subject->level_class,
                    'average_value' => 0,
                    'attendance'    => 0,
                    'mentor_note'   => null,
                ]
            );

            $teacherRubricIds = RubricCategory::where('teacher_id', $teacher->teacher_id)
                ->where('subject_id', $subjectId)
                ->pluck('rubric_id')
                ->toArray();

            foreach ($data['scores'] as $scoreData) {
                if (in_array($scoreData['rubric_id'], $teacherRubricIds)) {
                    ReportDetail::updateOrCreate(
                        [
                            'report_id' => $report->report_id,
                            'rubric_id' => $scoreData['rubric_id'],
                        ],
                        [
                            'score'               => $scoreData['score'],
                            'description_subject' => $scoreData['description_subject'] ?? '-',
                        ]
                    );
                }
            }
            $this->recalculateAverage($report->report_id);

            return $report->fresh();
        });
    }

    /**
     * Internal method untuk recalculate rata-rata
     */
    private function recalculateAverage(int $reportId): void
    {
        $average = ReportDetail::where('report_id', $reportId)->avg('score');
        
        Reports::where('report_id', $reportId)->update([
            'average_value' => $average ? round($average, 2) : 0
        ]);
    }
}
