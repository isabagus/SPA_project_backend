<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        foreach ($classes as $class) {
            LevelClass::table('class')->insert([
                'level_class' => $class,
            ]);
        }
    }
}
