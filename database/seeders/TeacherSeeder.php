<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            ['username' => 'teacher_ahmad', 'name' => 'Ahmad Fauzi, S.Pd', 'email' => 'ahmad@example.com'],
            ['username' => 'teacher_siti', 'name' => 'Siti Aminah, M.Pd', 'email' => 'siti@example.com'],
            ['username' => 'teacher_budi', 'name' => 'Budi Cahyono, S.Kom', 'email' => 'budi_t@example.com'],
            ['username' => 'teacher_dewi', 'name' => 'Dewi Lestari, S.Si', 'email' => 'dewi@example.com'],
            ['username' => 'teacher_eko', 'name' => 'Eko Prasetyo, M.Hum', 'email' => 'eko@example.com'],
            ['username' => 'teacher_fitri', 'name' => 'Fitri Handayani, S.Pd', 'email' => 'fitri@example.com'],
            ['username' => 'teacher_gani', 'name' => 'Gani Abdul, M.Si', 'email' => 'gani@example.com'],
            ['username' => 'teacher_huda', 'name' => 'Huda Nur, S.Pd', 'email' => 'huda@example.com'],
            ['username' => 'teacher_indah', 'name' => 'Indah Permata, M.Pd', 'email' => 'indah@example.com'],
            ['username' => 'teacher_joko', 'name' => 'Joko Susilo, S.Kom', 'email' => 'joko@example.com'],
        ];

        foreach ($teachers as $t) {
            $user = User::firstOrCreate(
                ['email' => $t['email']],
                [
                    'username' => $t['username'],
                    'password' => Hash::make('password123'),
                    'role' => 'teacher',
                    'phone_number' => '0812' . rand(10000000, 99999999),
                ]
            );

            Teacher::firstOrCreate(
                ['user_id' => $user->user_id],
                [
                    'name' => $t['name'],
                    'phone_number' => $user->phone_number,
                ]
            );
        }
    }
}
