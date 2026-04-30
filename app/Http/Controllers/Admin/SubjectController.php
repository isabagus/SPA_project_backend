<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return view('layouts.subjects.index');
    }
<<<<<<< HEAD
    public function create() {
        return view('layouts.subjects.create');
    }
    public function store() {}
    public function update() {}
    public function edit() {}
    public function delete() {}
=======

     public function detail()
    {
        return view('layouts.subjects.detail');
    }
    
    public function create()
    {
        return view('layouts.subject.create');
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
>>>>>>> 54c2d1b8e3839cac0a07e8feb8d33836ea605ce2
}
