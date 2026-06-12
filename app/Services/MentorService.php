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

        $affectiveSubject = Subject::whereIn('category_subject', ['Affective Domain', 'Affective Domain RS & PKN'])
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
        // 1. Security Check
        $student = Student::where('student_id', $studentId)
            ->where(function($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId)
                      ->orWhereHas('levelClass', function($q) use ($mentorId) {
                          $q->where('mentor_id', $mentorId);
                      });
            })
            ->firstOrFail();

        // 2. Ambil semua subjek yang ada di kelas siswa tersebut
        $subjects = Subject::where('class_id', $student->class_id)
            ->with(['teacher', 'rubrics.criteria'])
            ->get();

        // Filter subjek agar hanya menampilkan Agama yang sesuai dengan siswa (dan PKN/lainnya)
        $studentReligion = $student->religion_name ? trim(strtolower($student->religion_name)) : '';
        $filteredSubjects = $subjects->filter(function($subject) use ($studentReligion) {
            $catName = strtolower($subject->category_subject);
            
            if (str_starts_with($catName, 'religion') || str_starts_with($catName, 'agama')) {
                if (empty($studentReligion)) return false;

                $religions = ['islam', 'christian', 'catholic', 'hindu', 'buddha', 'konghucu'];
                foreach ($religions as $rel) {
                    if (str_contains($catName, $rel)) {
                        return str_contains($studentReligion, $rel) || str_contains($rel, $studentReligion);
                    }
                }
                
                return str_contains($catName, $studentReligion);
            }
            
            return true;
        });

        // 3. Ambil laporan yang sudah ada
        $existingReports = Reports::where('student_id', $studentId)
            ->with(['reportDetails.rubric', 'reportDetails.criteria.category'])
            ->get()
            ->keyBy('subject_id');

        // 4. Gabungkan: Tampilkan subjek meskipun belum ada report-nya
        return $filteredSubjects->map(function ($subject) use ($existingReports, $studentId, $student) {
            if ($existingReports->has($subject->subject_id)) {
                $report = $existingReports->get($subject->subject_id);
                // Pastikan subject di dalam report adalah subject yang lengkap dengan teacher
                $report->subject = $subject;
                $report->student = $student;
                return $report;
            }

            // Jika belum ada report, buat "Phantom Report" agar rubriknya muncul di Mentor
            $report_details = [];
            foreach ($subject->rubrics as $rubric) {
                foreach ($rubric->criteria as $criteria) {
                    $report_details[] = [
                        'id' => 0, // Virtual ID
                        'rubric_id' => $rubric->rubric_id,
                        'criteria_id' => $criteria->criteria_id,
                        'score' => 0,
                        'description_subject' => null,
                        'rubric' => $rubric,
                        'criteria' => $criteria,
                    ];
                }
            }

            return (object) [
                'report_id' => 0,
                'student_id' => $studentId,
                'subject_id' => $subject->subject_id,
                'average_value' => 0,
                'academic_year' => '-',
                'subject' => $subject,
                'student' => $student,
                'report_details' => $report_details
            ];
        })->values();
    }

    /**
     * Get detail of one subject for a student (Mentor View)
     */
    public function getSubjectDetail($mentorId, $studentId, $reportId)
    {
        // Security Check: Pastikan siswa ini di bawah bimbingan mentor ini
        $student = Student::where('student_id', $studentId)
            ->where(function($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId)
                      ->orWhereHas('levelClass', function($q) use ($mentorId) {
                          $q->where('mentor_id', $mentorId);
                      });
            })
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

        $affectiveSubject = Subject::whereIn('category_subject', ['Affective Domain', 'Affective Domain RS & PKN'])
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
        $affectiveSubject = Subject::whereIn('category_subject', ['Affective Domain', 'Affective Domain RS & PKN'])
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
    public function updateReportDetailDescription($mentorId, $studentId, $detailId, $description, $subjectId = null, $criteriaId = null)
    {
        // Security Check: Pastikan siswa ini di bawah bimbingan mentor ini
        $student = Student::where('student_id', $studentId)
            ->where(function($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId)
                      ->orWhereHas('levelClass', function($q) use ($mentorId) {
                          $q->where('mentor_id', $mentorId);
                      });
            })
            ->firstOrFail();

        if ($detailId == 0 && $subjectId && $criteriaId) {
            // Phantom Detail: Create the report and detail on the fly
            $subject = Subject::findOrFail($subjectId);
            
            $report = Reports::firstOrCreate(
                ['student_id' => $studentId, 'subject_id' => $subjectId],
                [
                    'class_id'      => $student->class_id,
                    'level_class'   => $subject->level_class,
                    'academic_year' => '2024/2025',
                    'average_value' => 0,
                    'attendance'    => 0,
                ]
            );

            $detail = ReportDetail::firstOrCreate(
                ['report_id' => $report->report_id, 'criteria_id' => $criteriaId],
                ['score' => 0, 'description_subject' => '']
            );
        } else {
            $detail = ReportDetail::where('id', $detailId)
                ->whereHas('report', function($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                })
                ->firstOrFail();
        }

        $detail->update([
            'description_subject' => $description
        ]);

        return $detail;
    }
}
