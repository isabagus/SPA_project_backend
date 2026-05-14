<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LevelClass;
use App\Models\Mentor;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['level' => 'Year 1', 'section' => '-'],
            ['level' => 'Year 2', 'section' => '-'],
        ];
        
        foreach ($classes as $idx => $classData) {
            $levelName = $classData['level'];
            $sectionName = $classData['section'];
            
            // Full name format e.g. "Year 1" or "Year 1-A"
            $fullName = ($sectionName === '-') ? $levelName : "{$levelName}-{$sectionName}";

            LevelClass::updateOrCreate(
                ['level_class' => $fullName],
                [
                    'level_name'   => $levelName,
                    'section_name' => $sectionName,
                    'mentor_id'    => null, // Diisi manual oleh Admin nanti
                ]
            );
        }
    }
}
