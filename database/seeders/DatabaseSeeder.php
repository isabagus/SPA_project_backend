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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        // Student::factory()->create([
        //     'year_academy' => '2026',
        //     'id_mentors' => '1',
        //     'name_student' => 'Student 1',
        //     'nis' => '123456789',
        //     'gender' => 'Male',
        //     'address' => '123 Main St',
        //     'photo' => 'student1.jpg',
        //     'email' => 'student1@gmail.com',
        // ]);
    }
}

