<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Dummy User untuk Mentor 1
        $user1 = User::firstOrCreate(
            ['email' => 'mentor1@example.com'],
            [
                'username' => 'mentor_agus',
                'password' => Hash::make('password123'),
                'role' => 'mentor',
                'phone_number' => '081234567890',
            ]
        );

        // 2. Buat Data Mentor 1
        Mentor::firstOrCreate(
            ['user_id' => $user1->user_id], // Assuming user_id is the primary key of User table based on previous context
            [
                'name_mentor' => 'Agus Setiawan, M.Pd.',
                'nip' => '198001012005011001',
                'phone_number' => '081234567890',
            ]
        );

        // 1. Buat Dummy User untuk Mentor 2
        $user2 = User::firstOrCreate(
            ['email' => 'mentor2@example.com'],
            [
                'username' => 'mentor_budi',
                'password' => Hash::make('password123'),
                'role' => 'mentor',
                'phone_number' => '081298765432',
            ]
        );

        // 2. Buat Data Mentor 2
        Mentor::firstOrCreate(
            ['user_id' => $user2->user_id],
            [
                'name_mentor' => 'Budi Santoso, S.Kom.',
                'nip' => '198502022010011002',
                'phone_number' => '081298765432',
            ]
        );
    }
}
