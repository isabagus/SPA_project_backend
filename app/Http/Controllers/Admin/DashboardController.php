<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Mentor;
use App\Models\Teacher;
use App\Models\LevelClass;
use App\Models\Subject;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_mentors'  => Mentor::count(),
            'total_teachers' => Teacher::count(),
            'total_classes'  => LevelClass::count(),
            'total_subjects' => Subject::count(),
        ];

        return view('layouts.dashboard.index', compact('stats'));
    }

    public function create()
    {
    }
    public function store()
    {

    }
    public function update()
    {

    }
    public function edit()
    {

    }
    public function delete()
    {

    }
}
