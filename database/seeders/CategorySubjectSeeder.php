<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'Aesthethics Domain',
            'Bahasa Indonesia',
            'Affective Domain RS PKN',
            'Chinese Language',
            'English',
            'Mathematics'
        ];

        foreach ($categories as $category) {
            DB::table('categories_subject')->updateOrInsert(
                ['category_subject' => $category],
                ['category_subject' => $category]
            );
        }
    }
}
