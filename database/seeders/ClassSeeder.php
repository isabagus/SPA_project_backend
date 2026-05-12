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
        
        $mentors = Mentor::all();

        if ($mentors->isEmpty()) {
            return;
        }

        foreach ($classes as $idx => $classData) {
            $mentor = $mentors[$idx % $mentors->count()];
            $levelName = $classData['level'];
            $sectionName = $classData['section'];
            
            // Full name format e.g. "Year 1" or "Year 1-A"
            $fullName = ($sectionName === '-') ? $levelName : "{$levelName}-{$sectionName}";

            LevelClass::updateOrCreate(
                ['level_class' => $fullName],
                [
                    'level_name'   => $levelName,
                    'section_name' => $sectionName,
                    'mentor_id'    => $mentor->mentor_id
                ]
            );
        }
    }
}
