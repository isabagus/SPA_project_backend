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
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ComprehensiveAppSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia untuk nama yang familiar

        // ── CONFIGURATION (Ubah jumlah data di sini) ──────────────────────────
        $count = [
            'teachers' => 10,
            'mentors'  => 5,
            'students' => 50, // Setiap murid akan ditarik ke minimal 1 orang tua
            'subjects' => 8,  // Jumlah mata pelajaran per level_class
        ];

        $this->command->info('Memulai seeding data komprehensif...');

        // 0. Disable Foreign Key Checks (PostgreSQL equivalent)
        DB::statement('SET session_replication_role = replica;');
        
        // Bersihkan tabel agar jumlah data sesuai dengan config $count
        ReportDetail::truncate(); 
        Reports::truncate(); 
        Parents::truncate();
        Student::truncate(); 
        Subject::truncate(); 
        Teacher::truncate(); 
        Mentor::truncate(); 
        User::where('role', '!=', 'admin')->delete(); // Hapus semua kecuali admin

        // 1. Buat Akun Admin (Tetap)
        User::updateOrCreate(['email' => 'admin@gmail.com'], [
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // 2. Buat Data Mentors & Users
        $mentors = [];
        for ($i = 1; $i <= $count['mentors']; $i++) {
            $user = User::create([
                'username' => 'mentor' . $i,
                'email' => "mentor$i@gmail.com",
                'password' => Hash::make('password123'),
                'role' => 'mentor'
            ]);

            $mentors[] = Mentor::create([
                'user_id' => $user->user_id,
                'name' => $faker->name,
                'nip' => $faker->unique()->numerify('##########'),
                'phone_number' => $faker->numerify('08##########')
            ]);
        }

        // 3. Buat Data Teachers & Users
        $teachers = [];
        for ($i = 1; $i <= $count['teachers']; $i++) {
            $user = User::create([
                'username' => 'teacher' . $i,
                'email' => "teacher$i@gmail.com",
                'password' => Hash::make('password123'),
                'role' => 'teacher'
            ]);

            $teachers[] = Teacher::create([
                'user_id' => $user->user_id,
                'name' => $faker->name,
                'phone_number' => $faker->numerify('08##########')
            ]);
        }

        // 4. Buat Subjects
        $subjects = [];
        $subjectNames = ['Mathematics', 'English', 'Science', 'Social Studies', 'Arts', 'Physical Education', 'ICT', 'Music'];
        $levels = ['Year 1', 'Year 2', 'Year 3', 'Year 4', 'Year 5', 'Year 6'];

        foreach ($levels as $level) {
            foreach ($subjectNames as $subName) {
                $subjects[] = Subject::create([
                    'category_subject' => $subName,
                    'level_class' => $level,
                    'term' => 'Term 1',
                    'teacher_id' => $faker->randomElement($teachers)->teacher_id
                ]);
            }
        }

        // 5. Buat Students & Religions
        $students = [];
        $religions = DB::table('religions')->pluck('religion_name')->toArray();
        if (empty($religions)) $religions = ['Islam', 'Christian', 'Catholic', 'Buddhism', 'Hinduism'];

        for ($i = 1; $i <= $count['students']; $i++) {
            $students[] = Student::create([
                'nis' => $faker->unique()->numerify('#####'),
                'name_student' => $faker->name,
                'academic_year' => '2023/2024',
                'level_class' => $faker->randomElement($levels),
                'religion_name' => $faker->randomElement($religions),
                'mentor_id' => $faker->randomElement($mentors)->mentor_id,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'address' => $faker->address,
                'phone_number' => $faker->numerify('08##########')
            ]);
        }

        // 6. Buat Parents & Link to Students
        foreach ($students as $index => $student) {
            // Kita buat 1 orang tua bisa punya 1 atau 2 anak (acak)
            $user = User::updateOrCreate(
                ['email' => "parent" . ($index % 25 + 1) . "@gmail.com"], // 25 orang tua untuk 50 siswa
                [
                    'username' => 'parent' . ($index % 25 + 1),
                    'password' => Hash::make('password123'),
                    'role' => 'parent'
                ]
            );

            Parents::create([
                'user_id' => $user->user_id,
                'student_id' => $student->student_id,
                'name_parent' => 'Parent of ' . $student->name_student
            ]);
        }

        // 7. Buat Rubrics, Reports, & Details (Skenario: Setiap murid punya nilai di matpel kelasnya)
        foreach ($students as $student) {
            // Ambil mata pelajaran yang sesuai dengan level_class murid
            $studentSubjects = array_filter($subjects, function($s) use ($student) {
                return $s->level_class === $student->level_class;
            });

            // Ambil 3 matpel saja per murid agar tidak terlalu berat, tapi data tetap bervariasi
            $sampledSubjects = $faker->randomElements($studentSubjects, 3);

            foreach ($sampledSubjects as $subject) {
                // Buat Report Header
                $report = Reports::create([
                    'student_id' => $student->student_id,
                    'subject_id' => $subject->subject_id,
                    'academic_year' => '2023/2024',
                    'level_class' => $student->level_class,
                    'average_value' => 0, // Akan diupdate nanti
                    'attendance' => $faker->numberBetween(80, 100),
                    'mentor_note' => $faker->sentence(10)
                ]);

                // Buat Rubric Categories & Criteria (3 per matpel)
                $scores = [];
                for ($j = 1; $j <= 3; $j++) {
                    $category = RubricCategory::firstOrCreate([
                        'subject_id' => $subject->subject_id,
                        'rubric_name' => 'Category ' . $j,
                        'term' => $subject->term
                    ], ['teacher_id' => $subject->teacher_id]);

                    $criteria = RubricCriteria::firstOrCreate([
                        'rubric_id' => $category->rubric_id,
                        'criteria_name' => 'Criteria ' . $faker->word . ' ' . $j
                    ], ['default_description' => 'Evaluasi default']);

                    $score = $faker->randomFloat(2, 1, 3);
                    $scores[] = $score;

                    ReportDetail::create([
                        'report_id' => $report->report_id,
                        'criteria_id' => $criteria->criteria_id,
                        'rubric_id' => $category->rubric_id,
                        'score' => $score,
                        'description_subject' => $faker->sentence(5)
                    ]);
                }

                // Update Average Value
                $report->update(['average_value' => collect($scores)->avg()]);
            }
        }

        DB::statement('SET session_replication_role = DEFAULT;');
        $this->command->info('Seeding selesai! Silakan gunakan akun parent1@gmail.com s/d parent25@gmail.com.');
    }
}
