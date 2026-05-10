<?php

namespace Database\Seeders;

use App\Models\Reports;
use App\Models\ReportDetail;
use App\Models\Student;
use App\Models\Subject;
use App\Models\RubricCategory;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 5 student pertama untuk diisi nilainya sebagai contoh
        $students = Student::limit(5)->get();

        foreach ($students as $student) {
            // Ambil subject yang sesuai dengan kelas siswa
            $subjects = Subject::where('level_class', $student->level_class)->get();

            foreach ($subjects as $subject) {
                // Buat Report Header
                $report = Reports::firstOrCreate(
                    [
                        'student_id' => $student->student_id,
                        'subject_id' => $subject->subject_id,
                        'level_class' => $student->level_class,
                        'academic_year' => $student->academic_year,
                    ],
                    [
                        'average_value' => 0,
                        'attendance' => rand(90, 100),
                        'mentor_note' => 'Good performance in this subject.',
                    ]
                );

                // Ambil Rubrik untuk subject ini
                $rubrics = RubricCategory::where('subject_id', $subject->subject_id)->get();
                $totalScore = 0;

                foreach ($rubrics as $rubric) {
                    $score = rand(75, 98) / 10; // Generate score between 7.5 - 9.8
                    ReportDetail::updateOrCreate(
                        [
                            'report_id' => $report->report_id,
                            'rubric_id' => $rubric->rubric_id,
                        ],
                        [
                            'score' => $score,
                            'description_subject' => 'Student has demonstrated good understanding of this criteria.',
                        ]
                    );
                    $totalScore += $score;
                }

                // Update Average
                if ($rubrics->count() > 0) {
                    $report->update(['average_value' => round($totalScore / $rubrics->count(), 2)]);
                }
            }
        }
    }
}
