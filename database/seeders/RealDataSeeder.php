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
            'mentor' => [
                'name'     => 'Mentor Teacher',
                'email'    => 'mentor.teacher@piaget.sch.id',
                'username' => 'mentor',
            ],
        ];

        foreach ($teachers as $key => $t) {
            $user = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'username' => $t['username'],
                    'password' => Hash::make('password123'),
                    'role'     => $key === 'mentor' ? 'mentor' : 'teacher',
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
            'Affective Domain'      => 'mentor',
        ];

        $groupMap = [
            'PKN'                   => 'RS_PKN',
            'Religion (Islam)'      => 'RS_PKN',
            'Religion (Christianity)' => 'RS_PKN',
            'Religion (Catholicism)'  => 'RS_PKN',
        ];

        foreach ($subjectTeacherMap as $subjectName => $teacherKey) {
            $teacherId = $teachers[$teacherKey]['model']->teacher_id;
            $terms = ['Term 1', 'Term 2', 'Term 3', 'Term 4'];
            $levels = ['Year 1', 'Year 2'];

            foreach ($levels as $level) {
                foreach ($terms as $term) {
                    Subject::updateOrCreate(
                        ['category_subject' => $subjectName, 'level_class' => $level, 'term' => $term],
                        [
                            'teacher_id'       => $teacherId,
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
            // Standardized Religious Studies (Shared for all religions)
            'Religion' => [
                '_all_terms' => [
                    ['rubric_name' => 'Religious Studies / Agama', 'description' => 'Demonstrates good understanding of subject matter; Participates actively in lessons'],
                ],
            ],
        ];

        foreach ($rubricDefinitions as $subjectName => $termRubrics) {
            // Logic khusus untuk Religion: cari semua subjek yang mengandung kata 'Religion'
            if ($subjectName === 'Religion') {
                $subjects = Subject::where('category_subject', 'like', 'Religion%')->get();
                // Gunakan guru Valent sebagai default atau sesuai map jika perlu
            } else {
                $teacherKey = $subjectTeacherMap[$subjectName] ?? null;
                if (!$teacherKey) continue;
                $teacherId = $teachers[$teacherKey]['model']->teacher_id;
                $subjects = Subject::where('category_subject', $subjectName)->get();
            }

            foreach ($subjects as $subject) {
                // Tentukan teacherId untuk subjek ini (jika bukan blok Religion umum)
                $finalTeacherId = ($subjectName === 'Religion') 
                    ? $subject->teacher_id 
                    : $teachers[$subjectTeacherMap[$subjectName]]['model']->teacher_id;

                $rubricsToSeed = $termRubrics['_all_terms'] ?? $termRubrics[$subject->term] ?? [];

                foreach ($rubricsToSeed as $rubricData) {
                    $category = RubricCategory::updateOrCreate(
                        [
                            'subject_id'   => $subject->subject_id,
                            'rubric_name'  => $rubricData['rubric_name'],
                        ],
                        [
                            'teacher_id'   => $finalTeacherId,
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

        $this->command->info('✅ RealDataSeeder clean-up completed!');
    }
}
