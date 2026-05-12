<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Parents;
use App\Models\Subject;
use App\Models\RubricCategory;
use App\Models\RubricCriteria;
use App\Models\Reports;
use App\Models\ReportDetail;
use App\Models\Mentor;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class ParentFeatureSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat User Parent
        $parentUser = User::updateOrCreate(
            ['email' => 'parent@gmail.com'],
            [
                'username' => 'parent_test',
                'password' => Hash::make('parent123'),
                'role' => 'parent',
            ]
        );

        // 1a. Buat User Mentor & Mentor Record (Penting untuk Student)
        $mentorUser = User::updateOrCreate(
            ['email' => 'mentor@gmail.com'],
            [
                'username' => 'mentor_test',
                'password' => Hash::make('mentor123'),
                'role' => 'mentor',
            ]
        );

        $mentorRecord = Mentor::updateOrCreate(
            ['user_id' => $mentorUser->user_id],
            [
                'name' => 'Mentor Hartono',
                'nip' => '1987654321',
                'phone_number' => '08122334455'
            ]
        );

        // 1b. Buat User Teacher & Teacher Record
        $teacherUser = User::updateOrCreate(
            ['email' => 'teacher@gmail.com'],
            [
                'username' => 'teacher_test',
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
            ]
        );

        $teacherRecord = Teacher::updateOrCreate(
            ['user_id' => $teacherUser->user_id],
            [
                'name' => 'Guru Matematika',
                'phone_number' => '081234567890',
            ]
        );

        // 2. Buat 2 Anak (Students)
        $student1 = Student::updateOrCreate(
            ['nis' => '12345'],
            [
                'name_student' => 'Budi Junior',
                'academic_year' => '2023/2024',
                'level_class' => 'Year 1',
                'religion_name' => 'Islam',
                'mentor_id' => $mentorRecord->mentor_id,
                'gender' => 'Male',
                'address' => 'Singapore Street No. 1',
                'phone_number' => '08123456789',
            ]
        );

        $student2 = Student::updateOrCreate(
            ['nis' => '67890'],
            [
                'name_student' => 'Santi Junior',
                'academic_year' => '2023/2024',
                'level_class' => 'Year 2',
                'religion_name' => 'Christian',
                'mentor_id' => $mentorRecord->mentor_id,
                'gender' => 'Female',
                'address' => 'Singapore Street No. 2',
                'phone_number' => '08987654321',
            ]
        );

        // 3. Hubungkan Parent ke Anak
        Parents::updateOrCreate(
            ['user_id' => $parentUser->user_id, 'student_id' => $student1->student_id],
            ['name_parent' => 'Wali dari Budi']
        );

        Parents::updateOrCreate(
            ['user_id' => $parentUser->user_id, 'student_id' => $student2->student_id],
            ['name_parent' => 'Wali dari Santi']
        );

        // 4. Buat Mata Pelajaran (Subjects)
        $math = Subject::updateOrCreate(
            ['category_subject' => 'Mathematics', 'level_class' => 'Year 1'],
            ['term' => 'Term 1', 'teacher_id' => $teacherRecord->teacher_id]
        );

        $english = Subject::updateOrCreate(
            ['category_subject' => 'English', 'level_class' => 'Year 1'],
            ['term' => 'Term 1', 'teacher_id' => $teacherRecord->teacher_id]
        );

        // 5. Buat Struktur Rubrik & Nilai untuk Budi (Anak ke-1)
        $this->createReportForStudent($student1, $math, [
            ['cat' => 'Number Sense', 'crit' => 'Understanding Addition', 'score' => 2.80],
            ['cat' => 'Number Sense', 'crit' => 'Understanding Subtraction', 'score' => 2.50],
            ['cat' => 'Geometry', 'crit' => 'Recognizing Shapes', 'score' => 3.00],
        ], 'Budi menunjukkan kemampuan matematika yang sangat baik, terutama di bidang Geometri.');

        $this->createReportForStudent($student1, $english, [
            ['cat' => 'Reading', 'crit' => 'Phonics Awareness', 'score' => 2.40],
            ['cat' => 'Writing', 'crit' => 'Letter Formation', 'score' => 2.60],
        ], 'Perlu latihan lebih banyak pada pelafalan phonics.');

        // 6. Buat Nilai untuk Santi (Anak ke-2)
        $this->createReportForStudent($student2, $math, [
            ['cat' => 'Logic', 'crit' => 'Pattern Recognition', 'score' => 2.90],
        ], 'Santi sangat cepat dalam mengenali pola logika.');
    }

    private function createReportForStudent($student, $subject, $criteriaList, $mentorNote)
    {
        // Buat Header Raport
        $report = Reports::updateOrCreate(
            [
                'student_id' => $student->student_id,
                'subject_id' => $subject->subject_id,
                'academic_year' => '2023/2024',
            ],
            [
                'level_class' => $student->level_class,
                'average_value' => collect($criteriaList)->avg('score'),
                'mentor_note' => $mentorNote,
                'attendance' => 100
            ]
        );

        foreach ($criteriaList as $item) {
            // Buat Category Rubrik
            $category = RubricCategory::updateOrCreate(
                [
                    'subject_id' => $subject->subject_id, 
                    'rubric_name' => $item['cat'],
                    'term' => $subject->term
                ],
                ['teacher_id' => $subject->teacher_id]
            );

            // Buat Criteria Rubrik
            $criteria = RubricCriteria::updateOrCreate(
                [
                    'rubric_id' => $category->rubric_id,
                    'criteria_name' => $item['crit']
                ],
                ['default_description' => 'Default evaluation for ' . $item['crit']]
            );

            // Isi Detail Nilai
            ReportDetail::updateOrCreate(
                [
                    'report_id' => $report->report_id,
                    'criteria_id' => $criteria->criteria_id,
                ],
                [
                    'rubric_id' => $category->rubric_id,
                    'score' => $item['score'],
                    'description_subject' => 'Good performance in ' . $item['crit']
                ]
            );
        }
    }
}
