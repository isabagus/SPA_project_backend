<?php

namespace Database\Seeders;

use App\Models\RubricCategory;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class RubricAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::all();
        $subjects = Subject::all();

        if ($teachers->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        foreach ($subjects as $subject) {
            // Get teacher from subject ownership
            $teacherId = $subject->teacher_id;

            if (!$teacherId) continue;

            // Create 3 rubrics for this assignment
            for ($i = 1; $i <= 3; $i++) {
                RubricCategory::firstOrCreate(
                    [
                        'subject_id' => $subject->subject_id,
                        'teacher_id' => $teacherId,
                        'rubric_name' => "Kriteria $i - " . $subject->category_subject,
                        'term' => $subject->term,
                    ]
                );
            }
        }
    }
}
