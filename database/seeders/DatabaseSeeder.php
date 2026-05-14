<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Data Master (Referensial)
        $this->call([
            AcademicYearSeeder::class,
            ReligionSeeder::class,
            TermSeeder::class,
            // MentorSeeder::class,   // Mentor dulu karena Class butuh mentor_id
            ClassSeeder::class,
            CategorySubjectSeeder::class, // Tambahkan ini agar Subject tidak error FK
            // SubjectSeeder::class,  // Subject butuh level_class & category_subject
        ]);

        // 2. Data Pengguna & Profil (Dummy)
        $this->call([
            // TeacherSeeder::class,
            // StudentSeeder::class,  // Student butuh Class & Mentor
            // ParentSeeder::class,   // Parent butuh Student
        ]);

        // 3. Operasional (Assignment & Score)
        $this->call([
            // RubricAssignmentSeeder::class, // Hubungkan Teacher ke Subject (dummy)
            // ScoreSeeder::class,            // Isi Nilai awal (dummy)
        ]);

        // 4. Real Data Override (dari CSV asli)
        // Meng-override data dummy dengan data asli dari laporan CSV.
        $this->call([
            // RealDataSeeder::class,
        ]);

        // 4. Admin User (Opsional jika belum ada)
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'username' => 'admin_super',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'phone_number' => '08111111111',
            ]
        );
    }
}
