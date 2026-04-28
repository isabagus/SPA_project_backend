<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('layouts.users.index');
    }
    public function create() {}
    public function store() {}
    public function update() {}
    public function edit() {}
    public function delete() {}
}
