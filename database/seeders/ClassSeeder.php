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

        foreach ($classes as $class) {
            LevelClass::updateOrCreate(
                ['level_class' => $class],
                ['level_class' => $class]
            );
        }
    }
}
