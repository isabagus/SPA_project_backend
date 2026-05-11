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
            'Mathematics',
            'English',
            'Bahasa Indonesia',
            'Chinese Language',
            'Aesthetics Domain',
            'RS & PKN',
            'Affective Domain'
        ];

        foreach ($categories as $category) {
            DB::table('categories_subject')->updateOrInsert(
                ['category_subject' => $category],
                ['category_subject' => $category]
            );
        }
    }
}
