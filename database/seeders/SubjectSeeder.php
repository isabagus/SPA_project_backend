<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LevelClass;
use App\Models\Subject;
use App\Models\Term;

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

        foreach ($years as $year) {
            foreach ($terms as $term) {
                foreach ($subjectNames as $name) {
                    Subject::updateOrCreate(
                        [
                            'category_subject' => $name,
                            'term'             => $term->term,
                            'level_class'         => $year->level_class,
                        ],
                        []
                    );
                }
            }
        }
    }
}
