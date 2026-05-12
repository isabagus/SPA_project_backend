<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\LevelClass;
use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\Mentor;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;

        $users = User::latest()
            ->when($keyword, function($query) use ($keyword) {
                $query->where('username', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('role', 'like', "%{$keyword}%");
            })
            ->paginate(10);
            
        return view('layouts.users.index', compact('users'));
    }

    public function create()
    {
        return view('layouts.users.create', [
            // Only show students who don't have a parent yet
            'students'      => Student::with(['levelClass', 'academicYear'])
                                ->whereDoesntHave('parent')
                                ->get(),
            'academicYears' => AcademicYear::all(),
            'classes'       => LevelClass::all(),
            'terms'         => \App\Models\Term::all(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'username'    => 'required|unique:users,username',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'role'        => 'required|in:admin,teacher,mentor,parent',
            'phone_number'=> 'nullable|string|max:20',
        ];

        // Conditional Validation based on Role
        if ($request->role === 'parent') {
            $rules['student_id']  = 'required|exists:students,student_id';
            $rules['name_parent'] = 'required|string|max:255';
        } elseif ($request->role === 'teacher') {
            $rules['name']         = 'required|string|max:255';
            $rules['phone_number'] = 'required|string|max:20';
        } elseif ($request->role === 'mentor') {
            $rules['name_mentor']  = 'required|string|max:255';
            $rules['nip']          = 'nullable|string|max:255';
            $rules['phone_number'] = 'nullable|string|max:20';
        }

        $data = $request->validate($rules);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'username'     => $data['username'],
                'email'        => $data['email'],
                'password'     => Hash::make($data['password']),
                'role'         => $data['role'],
                'phone_number' => $data['phone_number'] ?? null,
            ]);

            if ($user->role === 'parent') {
                $user->parent()->create([
                    'student_id'  => $data['student_id'],
                    'name_parent' => $data['name_parent'],
                ]);
            } elseif ($user->role === 'teacher') {
                $user->teacher()->create([
                    'name'         => $data['name'],
                    'phone_number' => $data['phone_number'],
                ]);
            } elseif ($user->role === 'mentor') {
                $user->mentor()->create([
                    'name_mentor'  => $data['name_mentor'],
                    'nip'          => $data['nip'],
                    'phone_number' => $data['phone_number'],
                ]);
            }
        });

        $roleTitle = ucfirst($request->role);
        $message = "{$roleTitle} account '{$request->username}' has been successfully created.";

        if ($request->action === 'save_another') {
            return redirect()->route('admin.users.create')->with('success', $message);
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message)
            ->with('new_user_id', User::latest('user_id')->first()->user_id);
    }

    public function edit($id)
    {
        $user = User::with(['parent.student', 'teacher', 'mentor'])->findOrFail($id);
        $currentStudentId = optional($user->parent)->student_id;

        return view('layouts.users.edit', [
            'user'          => $user,
            'students'      => Student::with(['levelClass', 'academicYear'])
                                ->where(function($query) use ($currentStudentId) {
                                    $query->whereDoesntHave('parent');
                                    if ($currentStudentId) {
                                        $query->orWhere('student_id', $currentStudentId);
                                    }
                                })->get(),
            'academicYears' => AcademicYear::all(),
            'classes'       => LevelClass::all(),
            'terms'         => \App\Models\Term::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'username' => ['required', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'password' => 'nullable|min:6',
            'role'     => 'required|in:admin,teacher,mentor,parent',
            'phone_number'=> 'nullable|string|max:20',
        ];

        // Conditional Validation based on Role
        if ($request->role === 'parent') {
            $rules['student_id']  = 'required|exists:students,student_id';
            $rules['name_parent'] = 'required|string|max:255';
        } elseif ($request->role === 'teacher') {
            $rules['name']         = 'required|string|max:255';
            $rules['phone_number'] = 'required|string|max:20';
        } elseif ($request->role === 'mentor') {
            $rules['name_mentor']  = 'required|string|max:255';
            $rules['nip']          = 'nullable|string|max:255';
            $rules['phone_number'] = 'nullable|string|max:20';
        }

        $data = $request->validate($rules);

        DB::transaction(function () use ($data, $user) {
            $userUpdateData = [
                'username' => $data['username'],
                'email'    => $data['email'],
                'role'     => $data['role'],
                'phone_number' => $data['phone_number'] ?? $user->phone_number,
            ];

            if (!empty($data['password'])) {
                $userUpdateData['password'] = Hash::make($data['password']);
            }
            
            $user->update($userUpdateData);

            if ($user->role === 'parent') {
                $user->parent()->updateOrCreate(['user_id' => $user->user_id], [
                    'student_id' => $data['student_id'],
                    'name_parent' => $data['name_parent']
                ]);
                $user->teacher()->delete();
                $user->mentor()->delete();
            } elseif ($user->role === 'teacher') {
                $user->teacher()->updateOrCreate(['user_id' => $user->user_id], [
                    'name' => $data['name'],
                    'phone_number' => $data['phone_number']
                ]);
                $user->parent()->delete();
                $user->mentor()->delete();
            } elseif ($user->role === 'mentor') {
                $user->mentor()->updateOrCreate(['user_id' => $user->user_id], [
                    'name_mentor' => $data['name_mentor'],
                    'nip' => $data['nip'],
                    'phone_number' => $data['phone_number']
                ]);
                $user->parent()->delete();
                $user->teacher()->delete();
            } else {
                $user->parent()->delete();
                $user->teacher()->delete();
                $user->mentor()->delete();
            }
        });

        $roleTitle = ucfirst($user->role);
        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->username}' ({$roleTitle}) has been successfully updated.")
            ->with('new_user_id', $user->user_id);
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
