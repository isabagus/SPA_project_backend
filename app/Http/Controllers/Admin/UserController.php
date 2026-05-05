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
        $users = User::all();
        return view('layouts.users.index', compact('users'));
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
        
        return redirect()->route('admin.users.index')->with('success', 'User Created Successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $students = Student::all();
        return view('layouts.users.edit', compact('user', 'students'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'username'    => ['required', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'email'       => ['required', 'email', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'password'    => 'nullable|min:6',
            'role'        => 'required',
            'student_id'  => 'required_if:role,parent',
            'name_parent' => 'required_if:role,parent',
        ];

        $data = $request->validate($rules);

        DB::transaction(function () use ($data, $user) {
            if (empty($data['password'])) {
                unset($data['password']);
            }
            
            $user->update($data);

            if ($user->role === 'parent') {
                $user->parent()->updateOrCreate(
                    ['user_id' => $user->user_id],
                    [
                        'student_id' => $data['student_id'],
                        'name_parent' => $data['name_parent']
                    ]
                );
            } else {
                $user->parent()->delete();
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User Updated Successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        DB::transaction(function () use ($user) {
            $user->parent()->delete();
            $user->mentor()->delete();
            $user->teacher()->delete();
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'User Deleted Successfully');
    }
}
