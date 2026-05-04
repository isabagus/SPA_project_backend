<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Term;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $terms = ['Term 1', 'Term 2', 'Term 3', 'Term 4'];

        foreach ($terms as $term) {
            Term::updateOrCreate(
                ['term' => $term],
                ['term' => $term]
            );
        }
    }
}
