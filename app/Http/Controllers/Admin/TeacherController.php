<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Database\Eloquent\Collection;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;

        $teachers = Teacher::with(['user'])
            ->latest()
            ->when($keyword, function ($query, $keyword) {
                return $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('phone_number', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($q) use ($keyword) {
                        $q->where('email', 'like', "%{$keyword}%")
                            ->orWhere('username', 'like', "%{$keyword}%");
                    });
            })->paginate(10)->withQueryString();

        return view('layouts.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $users = User::where('role', 'teacher')->doesntHave('teacher')->get();
        return view('layouts.teachers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ];

        // Jika tidak memilih user_id, maka wajib mengisi kredensial baru
        if (!$request->user_id) {
            $rules['username'] = 'required|string|max:255|unique:users,username';
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['user_id'] = 'required|exists:users,user_id';
        }

        $request->validate($rules);

        \DB::transaction(function () use ($request) {
            $userId = $request->user_id;

            if (!$userId) {
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => \Hash::make($request->password),
                    'role' => 'teacher',
                    'phone_number' => $request->phone_number,
                ]);
                $userId = $user->user_id;
            }

            Teacher::create([
                'user_id' => $userId,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully with user account.');
    }

    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $users = User::where('role', 'teacher')->doesntHave('teacher')
                     ->orWhere('user_id', $teacher->user_id)
                     ->get();

        return view('layouts.teachers.edit', compact('teacher', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update($request->all());

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully');
    }

    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        \DB::transaction(function () use ($teacher) {
            // Unassign teacher from subjects first to avoid foreign key constraint error
            \App\Models\Subject::where('teacher_id', $teacher->teacher_id)
                               ->update(['teacher_id' => null]);

            $user = $teacher->user;
            $teacher->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher and associated User account deleted successfully.');
    }
}
