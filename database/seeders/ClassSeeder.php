<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LevelClass;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = ['Year 1', 'Year 2'];
        $mentors = \App\Models\Mentor::all();

        if ($mentors->isEmpty()) {
            return;
        }

        foreach ($classes as $idx => $class) {
            // Assign mentor to class (round robin)
            $mentor = $mentors[$idx % $mentors->count()];

            LevelClass::updateOrCreate(
                ['level_class' => $class],
                [
                    'level_class' => $class,
                    'mentor_id'   => $mentor->mentor_id
                ]
            );
        }
    }
}
