<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mentor;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = Mentor::all();
        $emailMentor = User::where('role', 'mentor')->first();
        return view('layouts.mentors.index', compact('mentors','emailMentor'));
    }
    public function create()
    {
        $userMentor = User::where('role', 'mentor')->first();
        return view('layouts.mentors.create', compact('userMentor'));
    }
    public function store() {}
    public function edit($id) {
        $mentor = Mentor::findOrFail($id);
        return view('layouts.mentors.index');
    }
    public function update() {}
    public function delete() {}
}
