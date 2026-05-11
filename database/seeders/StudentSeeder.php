<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\LevelClass;
use App\Models\AcademicYear;
use App\Models\Religion;
use App\Models\Mentor;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = LevelClass::all();
        $academicYear = AcademicYear::first()->academic_year ?? '2023/2024';
        $religion = Religion::first()->religion_name ?? 'Islam';
        
        foreach ($classes as $class) {
            $mentorId = $class->mentor_id ?? Mentor::first()->mentor_id;
            
            for ($i = 1; $i <= 10; $i++) {
                Student::firstOrCreate(
                    [
                        'name_student' => "Student $i - " . $class->level_class,
                        'nis'          => "NIS-" . str_replace(' ', '', $class->level_class) . "-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                    ],
                    [
                        'academic_year' => $academicYear,
                        'level_class' => $class->level_class,
                        'religion_name' => $religion,
                        'mentor_id' => $mentorId,
                        'gender' => $i % 2 == 0 ? 'Laki-laki' : 'Perempuan',
                        'address' => "Address for Student $i in " . $class->level_class,
                        'phone_number' => '0898' . rand(10000000, 99999999),
                    ]
                );
            }
        }
    }
}
