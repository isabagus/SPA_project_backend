<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('layouts.users.index');
    }
    public function create()
    {
        $students = Student::all();
        return view('layouts.users.create', compact('students'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'username'    => 'required|unique:users',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:6',
            'role'        => 'required',
            'student_id'  => 'required_if:role,parent',
            'name_parent' => 'required_if:role,parent',
        ]);
        DB::transaction(function () use ($data) {

            $user = User::create($data);
            if ($user->role === 'parent') {
                $user->parent()->create($data);
            }
        });
        return redirect()->route('admin.users.index')->with('success', 'User Created');
    }
    public function update() {}
    public function edit() {}
    public function delete() {}
}
