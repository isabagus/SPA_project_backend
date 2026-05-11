<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\LevelClass;
use App\Models\Religion;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\RubricCategory;
use App\Models\RubricCriteria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting RealDataSeeder with Sub-Criteria Support...');

        // ============================================================
        // 1. DATA GURU (TEACHERS)
        // ============================================================
        $teachers = [
            'emmylou' => [
                'name'     => 'Ms. Emmylou',
                'email'    => 'emmylou@piaget.sch.id',
                'username' => 'emmylou',
            ],
            'katrin' => [
                'name'     => 'Ms. Katrin',
                'email'    => 'katrin@piaget.sch.id',
                'username' => 'katrin',
            ],
            'retno' => [
                'name'     => 'Ms. Retno',
                'email'    => 'retno@piaget.sch.id',
                'username' => 'retno',
            ],
            'valent' => [
                'name'     => 'Ms. Valent',
                'email'    => 'valent@piaget.sch.id',
                'username' => 'valent',
            ],
            'mentor' => [
                'name'     => 'Mentor Teacher',
                'email'    => 'mentor.teacher@piaget.sch.id',
                'username' => 'mentor.teacher',
            ],
        ];

        foreach ($teachers as $key => $t) {
            $user = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'username' => $t['username'],
                    'password' => Hash::make('password123'),
                    'role'     => 'teacher',
                ]
            );

            $teachers[$key]['model'] = Teacher::updateOrCreate(
                ['user_id' => $user->user_id],
                ['name' => $t['name'], 'phone_number' => '0812' . rand(10000000, 99999999)]
            );
        }

        // ============================================================
        // 2. DATA SISWA (STUDENTS)
        // ============================================================
        $studentsData = [
            ['nis' => 'Y1-001', 'name' => 'Emmy Kurniawan Lukminto', 'level_class' => 'Year 1', 'gender' => 'Perempuan', 'address' => '-'],
            ['nis' => 'Y2-001', 'name' => 'Adrian Li Preman', 'level_class' => 'Year 2', 'gender' => 'Laki-laki', 'address' => '-'],
        ];

        foreach ($studentsData as $s) {
            $levelClass = LevelClass::where('level_class', $s['level_class'])->first();
            if (!$levelClass) continue;
            $academicYear = AcademicYear::first()->academic_year ?? '2024/2025';
            $religion     = Religion::first()->religion_name ?? 'Islam';

            Student::updateOrCreate(
                ['nis' => $s['nis']],
                [
                    'name_student'  => $s['name'],
                    'academic_year' => $academicYear,
                    'level_class'   => $s['level_class'],
                    'religion_name' => $religion,
                    'gender'        => $s['gender'],
                    'address'       => $s['address'],
                    'phone_number'  => '0812' . rand(10000000, 99999999),
                    'mentor_id'     => $levelClass->mentor_id,
                ]
            );
        }

        // ============================================================
        // 3. MATA PELAJARAN (SUBJECTS)
        // ============================================================
        $subjectTeacherMap = [
            'English'             => 'emmylou',
            'Mathematics'         => 'emmylou',
            'Science'             => 'katrin',
            'Aesthetics Domain'   => 'katrin',
            'Bahasa Indonesia'    => 'retno',
            'RS & PKN'            => 'retno',
            'Chinese Language'    => 'valent',
            'Affective Domain'    => 'mentor',
        ];

        foreach ($subjectTeacherMap as $subjectName => $teacherKey) {
            $teacherId = $teachers[$teacherKey]['model']->teacher_id;
            $terms = ['Term 1', 'Term 2', 'Term 3', 'Term 4'];
            $levels = ['Year 1', 'Year 2'];

            foreach ($levels as $level) {
                foreach ($terms as $term) {
                    Subject::updateOrCreate(
                        ['category_subject' => $subjectName, 'level_class' => $level, 'term' => $term],
                        ['teacher_id' => $teacherId]
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
                    ['rubric_name' => 'Reading & Listening',     'description' => 'Understand the main idea of a text; identify specific information; follow simple instructions.'],
                    ['rubric_name' => 'Writing',                'description' => 'Write simple sentences with correct punctuation; use appropriate vocabulary.'],
                    ['rubric_name' => 'Speaking',               'description' => 'Express ideas clearly; participate in class discussions; use correct pronunciation.'],
                ],
            ],
            'Mathematics' => [
                'Term 1' => [
                    ['rubric_name' => 'Numbers to 10',             'description' => 'Count to 10; read and write numbers; compare and order numbers.'],
                    ['rubric_name' => 'Number Bonds',              'description' => 'Show number bonds to 10; recognise parts and wholes.'],
                ],
                'Term 2' => [
                    ['rubric_name' => 'Shapes & Patterns',         'description' => 'Identify common 2D shapes; group shapes by attributes; complete patterns.'],
                ],
            ],
            'Science' => [
                '_all_terms' => [
                    ['rubric_name' => 'Scientific Knowledge',    'description' => 'Demonstrate understanding of scientific concepts; apply knowledge to new situations.'],
                ],
            ],
        ];

        foreach ($rubricDefinitions as $subjectName => $termRubrics) {
            $teacherKey = $subjectTeacherMap[$subjectName] ?? null;
            if (!$teacherKey) continue;
            
            $teacherId = $teachers[$teacherKey]['model']->teacher_id;
            $subjects = Subject::where('category_subject', $subjectName)->get();

            foreach ($subjects as $subject) {
                $subjectTerm = $subject->term;
                $rubrics = $termRubrics['_all_terms'] ?? $termRubrics[$subjectTerm] ?? null;
                if (!$rubrics) continue;

                foreach ($rubrics as $rubricData) {
                    // 1. Create/Update Parent (Rubric Category)
                    $category = RubricCategory::updateOrCreate(
                        [
                            'subject_id'   => $subject->subject_id,
                            'teacher_id'   => $teacherId,
                            'rubric_name'  => $rubricData['rubric_name'],
                            'term'         => $subjectTerm,
                        ]
                    );

                    // 2. Create/Update Children (Rubric Criteria)
                    // Kita pecah deskripsi dari CSV menjadi sub-kriteria
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

        $this->command->info('✅ RealDataSeeder with Sub-Criteria completed!');
    }
}
