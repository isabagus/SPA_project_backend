<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\PaginationServiceProvider;
use App\Models\User;
use App\Models\Parents;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Mentor;
use App\Models\LevelClass;
use App\Models\Religion;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index()
    {
        $parents = Parents::with(['user', 'student'])->latest()->paginate(10);
        return view('layouts.parents.index', compact('parents'));
    }

    public function create()
    {
        $usersParent = User::where('role', 'parent')->doesntHave('parent')->get();
        $students = Student::all();
        $academic_years = AcademicYear::all();
        $mentors = Mentor::all();
        $level_classes = LevelClass::all();
        $religions = Religion::all();
        
        return view('layouts.parents.create', compact('usersParent', 'students', 'academic_years', 'mentors', 'level_classes', 'religions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name_parent' => 'required|string|max:150',
        ];

        // Account Logic
        if (!$request->user_id) {
            $rules['username'] = 'required|string|max:255|unique:users,username';
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['user_id'] = 'required|exists:users,user_id|unique:parents,user_id';
        }

        // Student Logic
        if (!$request->student_id) {
            $rules['name_student'] = 'required|string|max:100';
            $rules['gender'] = 'required|string|max:15';
            $rules['address'] = 'required|string|max:255';
            $rules['nis'] = 'required|string|max:255|unique:students,nis';
            $rules['phone_number'] = 'required|string|max:15';
            $rules['academic_year'] = 'required|exists:academic_years,academic_year';
            $rules['class_id'] = 'required|exists:classes,class_id';
            $rules['religion_name'] = 'required|exists:religions,religion_name';
        } else {
            $rules['student_id'] = 'required|exists:students,student_id';
        }

        $request->validate($rules);

        \DB::transaction(function () use ($request) {
            $userId = $request->user_id;

            // 1. Create User if needed
            if (!$userId) {
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => \Hash::make($request->password),
                    'role' => 'parent',
                ]);
                $userId = $user->user_id;
            }

            // 2. Create Student if needed
            $studentId = $request->student_id;
            if (!$studentId) {
                $levelClass = LevelClass::find($request->class_id);

                $student = Student::create([
                    'name_student' => $request->name_student,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'nis' => $request->nis,
                    'phone_number' => $request->phone_number,
                    'academic_year' => $request->academic_year,
                    'class_id' => $levelClass->class_id,
                    'level_class' => $levelClass->level_class,
                    'religion_name' => $request->religion_name,
                    'mentor_id' => $levelClass->mentor_id,
                ]);
                $studentId = $student->student_id;
            }

            // 3. Create Parent
            Parents::create([
                'user_id' => $userId,
                'student_id' => $studentId,
                'name_parent' => $request->name_parent,
            ]);
        });

        return redirect()->route('admin.parents.index')->with('success', 'Parent profile and account created successfully.');
    }

    public function edit($id)
    {
        $parent = Parents::findOrFail($id);

        $usersParent = User::where('role', 'parent')
            ->where(function($query) use ($parent) {
                $query->doesntHave('parent')
                      ->orWhere('user_id', $parent->user_id);
            })->get();
            
        $students = Student::all();

        return view('layouts.parents.edit', compact('parent', 'usersParent', 'students'));
    }


    public function update(Request $request, $id)
    {
        $parent = Parents::findOrFail($id);

        $data = $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:parents,user_id,' . $parent->parent_id . ',parent_id',
            'student_id' => 'required|exists:students,student_id',
            'name_parent' => 'required|string|max:150',
        ]);

        $parent->update($data);

        return redirect()->route('admin.parents.index')->with('success', 'Parent profile updated successfully.');
    }

    public function destroy($id)
    {
        $parent = Parents::findOrFail($id);
        
        \DB::transaction(function () use ($parent) {
            $user = $parent->user;
            $parent->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.parents.index')->with('success', 'Parent profile and associated User account deleted successfully.');
    }
}
