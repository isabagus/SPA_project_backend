<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LevelClass;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Teacher;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjectNames = [
            'Science',
            'Affective Domain',
            'Aesthethics Domain',
            'Bahasa Indonesia',
            'Affective Domain RS PKN',
            'Chinese Language',
            'English',
            'Mathematics'
        ];

        $years = LevelClass::all();
        $terms = Term::all();
        $teachers = Teacher::all();

        if ($teachers->isEmpty()) {
            return;
        }

        $teacherCount = $teachers->count();
        $iterator = 0;

        foreach ($years as $year) {
            foreach ($terms as $term) {
                foreach ($subjectNames as $name) {
                    // Assign 1 teacher to each subject (round robin)
                    $teacher = $teachers[$iterator % $teacherCount];
                    
                    Subject::updateOrCreate(
                        [
                            'category_subject' => $name,
                            'term'             => $term->term,
                            'class_id'         => $year->class_id,
                        ],
                        [
                            'level_class'      => $year->level_class,
                            'teacher_id'       => $teacher->teacher_id
                        ]
                    );
                    
                    $iterator++;
                }
            }
        }
    }
}
