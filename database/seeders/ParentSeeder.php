<?php

namespace Database\Seeders;

use App\Models\Parents;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 10 student pertama sebagai contoh
        $students = Student::limit(10)->get();

        foreach ($students as $idx => $student) {
            $user = User::firstOrCreate(
                ['email' => "parent$idx@example.com"],
                [
                    'username' => "parent_" . strtolower(explode(' ', $student->name_student)[0]),
                    'password' => Hash::make('password123'),
                    'role' => 'parent',
                    'phone_number' => '0855' . rand(10000000, 99999999),
                ]
            );

            Parents::firstOrCreate(
                ['user_id' => $user->user_id],
                [
                    'student_id' => $student->student_id,
                    'name_parent' => "Parent of " . $student->name_student,
                    // 'phone_number' => $user->phone_number,
                ]
            );
        }
    }
}
