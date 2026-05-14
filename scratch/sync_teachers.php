<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;

$subjects = Subject::all();
foreach ($subjects as $subject) {
    $rubric = $subject->rubrics()->first();
    if ($rubric) {
        $subject->teacher_id = $rubric->teacher_id;
        $subject->save();
        echo "Updated Subject ID: {$subject->subject_id} with Teacher ID: {$rubric->teacher_id}\n";
    }
}
