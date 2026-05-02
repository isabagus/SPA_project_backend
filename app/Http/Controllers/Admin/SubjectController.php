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

    public function create() {
        return view('layouts.subjects.create');
    }
   
     public function detail()
    {
        return view('layouts.subjects.detail');
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
