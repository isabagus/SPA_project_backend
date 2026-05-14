<?php

namespace App\Services;

use App\Models\Mentor;
use App\Models\LevelClass;
use App\Models\Student;
use App\Models\Reports;
use App\Models\Subject;
use App\Models\RubricCategory;
use App\Models\ReportDetail;

class MentorService
{
    /**
     * Mengambil daftar kelas yang dibimbing oleh mentor
     */
    public function getMentorClasses(Mentor $mentor)
    {
        return LevelClass::where('mentor_id', $mentor->mentor_id)
            ->get(['level_class', 'mentor_id']);
    }

    /**
     * Mengambil daftar siswa dalam kelas mentor dengan status pengisian evaluasi
     */
    public function getStudentsInClass(Mentor $mentor, string $levelClass)
    {
        $class = LevelClass::where('level_class', $levelClass)
            ->where('mentor_id', $mentor->mentor_id)
            ->first();

        if (!$class) {
            return [];
        }

        $affectiveSubject = Subject::where('category_subject', 'Affective Domain')
            ->where('level_class', $levelClass)
            ->first();

        $religionSubjects = Subject::where('category_subject', 'LIKE', 'Religion%')
            ->where('level_class', $levelClass)
            ->get();

        return Student::where('level_class', $levelClass)
            ->orderBy('name_student')
            ->get()
            ->map(function ($student) use ($affectiveSubject, $religionSubjects) {
                $report = null;
                if ($affectiveSubject) {
                    $report = Reports::where('student_id', $student->student_id)
                        ->where('subject_id', $affectiveSubject->subject_id)
                        ->first();
                }

                $studentReligion = strtolower($student->religion_name);
                $isReligionCovered = $religionSubjects->contains(function($s) use ($studentReligion) {
                    return str_contains(strtolower($s->category_subject), $studentReligion);
                });

                return [
                    'student_id'        => $student->student_id,
                    'nis'               => $student->nis,
                    'name_student'      => $student->name_student,
                    'religion_name'     => $student->religion_name,
                    'status_note'       => $report && !empty($report->mentor_note) ? 'completed' : 'none',
                    'mentor_note'       => $report?->mentor_note,
                    'religion_fallback' => !$isReligionCovered,
                ];
            });
    }

    /**
     * Get student's full academic report (Summary for Mentor)
     */
    public function getStudentAcademicReport($mentorId, $studentId)
    {
        // Security Check: Pastikan siswa ini di bawah bimbingan mentor ini
        $student = Student::where('student_id', $studentId)
            ->where('mentor_id', $mentorId)
            ->firstOrFail();

        // Ambil semua raport (reports) untuk siswa tersebut
        return Reports::where('student_id', $studentId)
            ->with(['subject.teacher', 'reportDetails.rubric', 'reportDetails.criteria'])
            ->get();
    }

    /**
     * Get detail of one subject for a student (Mentor View)
     */
    public function getSubjectDetail($mentorId, $studentId, $reportId)
    {
        // Security Check: Pastikan siswa ini di bawah bimbingan mentor ini
        $student = Student::where('student_id', $studentId)
            ->where('mentor_id', $mentorId)
            ->firstOrFail();

        return Reports::where('report_id', $reportId)
            ->where('student_id', $studentId)
            ->with([
                'subject.teacher',
                'reportDetails.rubric',
                'reportDetails.criteria',
                'student.levelClass'
            ])
            ->firstOrFail();
    }

    /**
     * Mengambil data lengkap form evaluasi mentor (Notes + Rubrik Fallback jika ada)
     */
    public function getEvaluationForm(Mentor $mentor, int $studentId, string $levelClass)
    {
        $student = Student::findOrFail($studentId);

        $affectiveSubject = Subject::where('category_subject', 'Affective Domain')
            ->where('level_class', $levelClass)
            ->first();

        $report = null;
        if ($affectiveSubject) {
            $report = Reports::where('student_id', $studentId)
                ->where('subject_id', $affectiveSubject->subject_id)
                ->first();
        }

        $studentReligion = strtolower($student->religion_name);
        $isReligionCovered = Subject::where('category_subject', 'LIKE', "Religion%($student->religion_name)%")
            ->where('level_class', $levelClass)
            ->exists();

        $rubrics = [];
        if (!$isReligionCovered) {
            $religionSubject = Subject::where('category_subject', 'LIKE', "Religion%($student->religion_name)%")
                ->where('level_class', $levelClass)
                ->first();

            if ($religionSubject) {
                $rubrics = RubricCategory::with('criteria')
                    ->where('subject_id', $religionSubject->subject_id)
                    ->get()
                    ->map(fn($r) => [
                        'rubric_id'   => $r->rubric_id,
                        'rubric_name' => $r->rubric_name,
                        'is_mine'     => true,
                        'criteria'    => $r->criteria->map(fn($c) => [
                            'criteria_id'   => $c->criteria_id,
                            'criteria_name' => $c->criteria_name,
                            'is_mine'       => true,
                            'current_score' => ReportDetail::whereHas('report', fn($q) => $q->where('student_id', $studentId)->where('subject_id', $religionSubject->subject_id))
                                ->where('criteria_id', $c->criteria_id)
                                ->first()?->score,
                        ])
                    ]);
            }
        }

        return [
            'student'           => $student,
            'mentor_note'       => $report?->mentor_note,
            'religion_fallback' => !$isReligionCovered,
            'rubrics'           => $rubrics,
        ];
    }

    /**
     * Menyimpan atau mengupdate catatan mentor & nilai fallback
     */
    public function submitEvaluation(Mentor $mentor, int $studentId, string $levelClass, array $data)
    {
        $affectiveSubject = Subject::where('category_subject', 'Affective Domain')
            ->where('level_class', $levelClass)
            ->first();

        if ($affectiveSubject) {
            Reports::updateOrCreate(
                ['student_id' => $studentId, 'subject_id' => $affectiveSubject->subject_id],
                [
                    'class_id'      => $affectiveSubject->class_id,
                    'level_class'   => $levelClass,
                    'academic_year' => $data['academic_year'] ?? '2024/2025',
                    'mentor_note'   => $data['mentor_note'] ?? null,
                    'average_value' => 0,
                    'attendance'    => 0,
                ]
            );
        }

        if (!empty($data['scores'])) {
            $student = Student::find($studentId);
            $religionSubject = Subject::where('category_subject', 'LIKE', "Religion%($student->religion_name)%")
                ->where('level_class', $levelClass)
                ->first();

            if ($religionSubject) {
                $religionReport = Reports::updateOrCreate(
                    ['student_id' => $studentId, 'subject_id' => $religionSubject->subject_id],
                    [
                        'class_id'      => $religionSubject->class_id,
                        'level_class'   => $levelClass, 
                        'academic_year' => $data['academic_year'] ?? '2024/2025'
                    ]
                );

                foreach ($data['scores'] as $criteriaId => $score) {
                    ReportDetail::updateOrCreate(
                        ['report_id' => $religionReport->report_id, 'criteria_id' => $criteriaId],
                        ['score' => (float)$score, 'description_subject' => '']
                    );
                }
                
                $avg = $religionReport->reportDetails()->avg('score');
                $religionReport->update([
                    'average_value' => $avg ? round($avg, 2) : 0,
                    'class_id'      => $religionSubject->class_id,
                    'level_class'   => $levelClass,
                ]);
            }
        }

        return true;
    }

    /**
     * Update qualitative description for a specific rubric criteria (Mentor Access)
     */
    public function updateReportDetailDescription($mentorId, $studentId, $detailId, $description)
    {
        // Security Check: Pastikan siswa ini di bawah bimbingan mentor ini
        $student = Student::where('student_id', $studentId)
            ->where('mentor_id', $mentorId)
            ->firstOrFail();

        $detail = ReportDetail::where('id', $detailId)
            ->whereHas('report', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->firstOrFail();

        $detail->update([
            'description_subject' => $description
        ]);

        return $detail;
    }
}
