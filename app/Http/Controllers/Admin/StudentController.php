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
<<<<<<< HEAD
=======
    }
    public function update()
    {

    }
    public function edit()
    {

    }
    public function delete()
    {

>>>>>>> 54c2d1b8e3839cac0a07e8feb8d33836ea605ce2
    }
    public function update() {}
    public function edit() {}
    public function delete() {}
}
