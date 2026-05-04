<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategorySubject;

class CategorySubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Science',
            'Affective Domain',
            'Mathematics',
            'English',
            'Affective Domain RS and PKN',
            'Aesthetics Domain',
            'Chinese Language',
            'Bahasa Indonesia'
        ];

        foreach ($categories as $category) {
            CategorySubject::updateOrCreate(
                ['category_subject' => $category],
                ['category_subject' => $category]
            );
        }
    }
}
