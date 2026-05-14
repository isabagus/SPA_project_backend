<?php

use App\Models\Mentor;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::transaction(function() {
    $mentors = Mentor::all();
    $count = 0;
    
    foreach ($mentors as $mentor) {
        $exists = Teacher::where('user_id', $mentor->user_id)->exists();
        
        if (!$exists) {
            Teacher::create([
                'user_id'      => $mentor->user_id,
                'name'         => $mentor->name_mentor,
                'phone_number' => $mentor->phone_number,
            ]);
            echo "Created Teacher record for Mentor: {$mentor->name_mentor}\n";
            $count++;
        }
    }
    
    echo "Total $count mentors synchronized to teachers table.\n";
});
