<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            '2023',
            '2024',
            '2025',
            '2026',
        ];

        foreach ($years as $year) {
            AcademicYear::firstOrCreate([
                'academic_year' => $year
            ]);
        }
    }
}
