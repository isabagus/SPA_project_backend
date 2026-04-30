<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('layouts.students.index');
    }

    public function detail()
    {
        return view('layouts.students.detail');
    }
    public function create()
    {
        return view('layouts.students.create');
    }
    public function update() {}
    public function edit() {}
    public function delete() {}
}
