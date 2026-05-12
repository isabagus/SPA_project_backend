<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\LevelClass;
use App\Models\Religion;
use App\Models\Reports;
use App\Models\ReportDetail;
use App\Models\RubricCategory;
use App\Models\RubricCriteria;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Mentor;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RealDataSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. DATA GURU (TEACHERS)
        // ============================================================
        $teachers = [
            'emmylou' => [
                'name'     => 'Ms. Emmylou Gasper',
                'email'    => 'emmylou.gasper@piaget.sch.id',
                'username' => 'emmylou',
            ],
            'katrin' => [
                'name'     => 'Ms. Katrin Setyawati',
                'email'    => 'katrin.setyawati@piaget.sch.id',
                'username' => 'katrin',
            ],
            'retno' => [
                'name'     => 'Ms. Retno Nur Indah',
                'email'    => 'retno.indah@piaget.sch.id',
                'username' => 'retno',
            ],
            'valent' => [
                'name'     => 'Ms. Valentina',
                'email'    => 'valentina@piaget.sch.id',
                'username' => 'valent',
            ],
            'mentor_a' => [
                'name'     => 'Ms. Mentor Year 1',
                'email'    => 'mentor.a@piaget.sch.id',
                'username' => 'mentor_a',
            ],
            'mentor_b' => [
                'name'     => 'Mr. Mentor Year 2',
                'email'    => 'mentor.b@piaget.sch.id',
                'username' => 'mentor_b',
            ],
        ];

        foreach ($teachers as $key => $t) {
            $isMentor = (strpos($key, 'mentor') !== false);
            
            $user = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'username' => $t['username'],
                    'password' => Hash::make('password123'),
                    'role'     => $isMentor ? 'mentor' : 'teacher',
                ]
            );

            // Always create Teacher record for grading ownership
            $teacherModel = Teacher::updateOrCreate(
                ['user_id' => $user->user_id],
                ['name' => $t['name'], 'phone_number' => '0812' . rand(10000000, 99999999)]
            );
            $teachers[$key]['teacher_model'] = $teacherModel;

            if ($isMentor) {
                $teachers[$key]['mentor_model'] = Mentor::updateOrCreate(
                    ['user_id' => $user->user_id],
                    [
                        'name_mentor'  => $t['name'], 
                        'nip'          => 'MNT-' . rand(1000, 9999),
                        'phone_number' => '0812' . rand(10000000, 99999999)
                    ]
                );
            }
        }

        // ============================================================
        // 2. DATA KELAS & SISWA
        // ============================================================
        $classesData = [
            ['level' => 'Year 1', 'section' => '-', 'mentor_key' => 'mentor_a'],
            ['level' => 'Year 2', 'section' => '-', 'mentor_key' => 'mentor_b'],
        ];

        foreach ($classesData as $c) {
            $mentorId = $teachers[$c['mentor_key']]['mentor_model']->mentor_id;
            $fullName = $c['section'] === '-' ? $c['level'] : "{$c['level']}-{$c['section']}";
            
            LevelClass::updateOrCreate(
                ['level_class' => $fullName],
                [
                    'level_name'   => $c['level'],
                    'section_name' => $c['section'],
                    'mentor_id'    => $mentorId
                ]
            );
        }

        $studentsData = [
            ['nis' => 'Y1-001', 'name' => 'Emmy Kurniawan Lukminto', 'level_class' => 'Year 1', 'gender' => 'Perempuan', 'address' => '-'],
            ['nis' => 'Y2-001', 'name' => 'Adrian Li Preman', 'level_class' => 'Year 2', 'gender' => 'Laki-laki', 'address' => '-'],
        ];

        Religion::updateOrCreate(['religion_name' => 'Buddhism']);

        foreach ($studentsData as $s) {
            $class = LevelClass::where('level_class', $s['level_class'])->first();
            if (!$class) continue;
            
            $academicYear = AcademicYear::first()->academic_year ?? '2024/2025';
            $religion = $s['nis'] === 'Y2-001' ? 'Buddhism' : 'Islam';

            Student::updateOrCreate(
                ['nis' => $s['nis']],
                [
                    'name_student'  => $s['name'],
                    'academic_year' => $academicYear,
                    'class_id'      => $class->class_id,
                    'level_class'   => $s['level_class'],
                    'religion_name' => $religion,
                    'gender'        => $s['gender'],
                    'address'       => $s['address'],
                    'phone_number'  => '0812' . rand(10000000, 99999999),
                    'mentor_id'     => $class->mentor_id,
                ]
            );
        }

        // ============================================================
        // 3. MATA PELAJARAN & GROUPING
        // ============================================================
        $subjectTeacherMap = [
            'English'               => 'emmylou',
            'Mathematics'           => 'emmylou',
            'Science'               => 'katrin',
            'Aesthetics Domain'     => 'katrin',
            'Bahasa Indonesia'      => 'retno',
            'PKN'                   => 'retno',
            'Religion (Islam)'      => 'valent',
            'Religion (Christianity)' => 'katrin',
            'Religion (Catholicism)'  => 'emmylou',
            'Chinese Language'      => 'valent',
            'Affective Domain'      => 'mentor_key', // Special flag
        ];

        $groupMap = [
            'PKN'                   => 'RS_PKN',
            'Religion (Islam)'      => 'RS_PKN',
            'Religion (Christianity)' => 'RS_PKN',
            'Religion (Catholicism)'  => 'RS_PKN',
        ];

        $classes = LevelClass::all();
        $terms = ['Term 1', 'Term 2', 'Term 3', 'Term 4'];

        foreach ($subjectTeacherMap as $subjectName => $teacherKey) {
            foreach ($classes as $class) {
                // If subject is Affective Domain, use the class mentor's TEACHER profile
                if ($subjectName === 'Affective Domain') {
                    // Find the teacher_id associated with the mentor of this class
                    $mentor = Mentor::find($class->mentor_id);
                    $teacherForMentor = Teacher::where('user_id', $mentor->user_id)->first();
                    $finalTeacherId = $teacherForMentor->teacher_id;
                } else {
                    $finalTeacherId = $teachers[$teacherKey]['teacher_model']->teacher_id;
                }

                foreach ($terms as $term) {
                    Subject::updateOrCreate(
                        [
                            'category_subject' => $subjectName, 
                            'class_id'         => $class->class_id, 
                            'term'             => $term
                        ],
                        [
                            'level_class'      => $class->level_class,
                            'teacher_id'       => $finalTeacherId,
                            'report_group_key' => $groupMap[$subjectName] ?? null
                        ]
                    );
                }
            }
        }

        // ============================================================
        // 4. RUBRIK & SUB-KRITERIA (RUBRIC CRITERIA)
        // ============================================================
        $rubricDefinitions = [
            'English' => [
                '_all_terms' => [
                    ['rubric_name' => 'Reading & Listening',     'description' => 'Understand the main idea; identify info; follow instructions.'],
                    ['rubric_name' => 'Writing',                'description' => 'Write simple sentences; use vocabulary.'],
                ],
            ],
            'PKN' => [
                '_all_terms' => [
                    ['rubric_name' => 'Civic Responsibility',    'description' => 'Understand rights and duties; show respect for national symbols.'],
                ],
            ],
            'Religion' => [
                '_all_terms' => [
                    ['rubric_name' => 'Religious Studies / Agama', 'description' => 'Demonstrates good understanding of subject matter; Participates actively in lessons'],
                ],
            ],
        ];

        foreach ($rubricDefinitions as $subjectName => $termRubrics) {
            if ($subjectName === 'Religion') {
                $subjects = Subject::where('category_subject', 'like', 'Religion%')->get();
            } else {
                $subjects = Subject::where('category_subject', $subjectName)->get();
            }

            foreach ($subjects as $subject) {
                $rubricsToSeed = $termRubrics['_all_terms'] ?? $termRubrics[$subject->term] ?? [];

                foreach ($rubricsToSeed as $rubricData) {
                    $category = RubricCategory::updateOrCreate(
                        [
                            'subject_id'   => $subject->subject_id,
                            'rubric_name'  => $rubricData['rubric_name'],
                        ],
                        [
                            'teacher_id'   => $subject->teacher_id,
                            'term'         => $subject->term,
                        ]
                    );

                    $criteriaItems = explode(';', $rubricData['description']);
                    foreach ($criteriaItems as $itemName) {
                        RubricCriteria::updateOrCreate(
                            [
                                'rubric_id'     => $category->rubric_id,
                                'criteria_name' => trim($itemName),
                            ]
                        );
                    }
                }
            }
        }

        // ============================================================
        // 5. GENERATE SCORES (REPORTS & DETAILS)
        // ============================================================
        $students = Student::all();
        foreach ($students as $student) {
            $subjects = Subject::where('class_id', $student->class_id)->get();
            
            foreach ($subjects as $subject) {
                // Create the main report record
                $report = Reports::updateOrCreate(
                    [
                        'student_id'    => $student->student_id,
                        'subject_id'    => $subject->subject_id,
                    ],
                    [
                        'class_id'      => $student->class_id,
                        'level_class'   => $student->level_class,
                        'academic_year' => $student->academic_year,
                        'average_value' => 0, // Will update after details
                        'attendance'    => rand(0, 2),
                        'mentor_note'   => (strpos($subject->category_subject, 'Affective') !== false) 
                                           ? "{$student->name_student} menunjukkan perilaku yang sangat baik dan aktif dalam kegiatan sekolah." 
                                           : null,
                    ]
                );

                // Create report details for each rubric criteria
                $rubricCategories = RubricCategory::where('subject_id', $subject->subject_id)->get();
                $totalScore = 0;
                $criteriaCount = 0;

                foreach ($rubricCategories as $cat) {
                    $criteria = RubricCriteria::where('rubric_id', $cat->rubric_id)->get();
                    foreach ($criteria as $crit) {
                        $score = number_format((rand(200, 300) / 100), 2); // Score between 2.00 - 3.00
                        
                        ReportDetail::updateOrCreate(
                            [
                                'report_id'     => $report->report_id,
                                'criteria_id'   => $crit->criteria_id,
                            ],
                            [
                                'rubric_id'           => $cat->rubric_id,
                                'score'               => $score,
                                'description_subject' => "Performance is consistent with the grade level expectations.",
                            ]
                        );
                        $totalScore += (float)$score;
                        $criteriaCount++;
                    }
                }

                // Update average based on real criteria scores (Scale 1-3)
                if ($criteriaCount > 0) {
                    $avgScore = $totalScore / $criteriaCount;
                    $report->update(['average_value' => round($avgScore, 2)]);
                }
            }
        }

        $this->command->info('✅ RealDataSeeder clean-up completed!');
    }
}
