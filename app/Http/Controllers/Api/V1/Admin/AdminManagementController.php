<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MentorResource;
use App\Http\Resources\V1\ParentResource;
use App\Http\Resources\V1\StudentResource;
use App\Http\Resources\V1\TeacherResource;
use App\Models\Mentor;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of the resource based on type.
     */
    public function index(Request $request)
    {
        $type = $request->query('type');

        switch ($type) {
            case 'mentor':
                $mentors = Mentor::with('user')->get();
                return MentorResource::collection($mentors);

            case 'teacher':
                $teachers = Teacher::with('user')->get();
                return TeacherResource::collection($teachers);

            case 'parent':
                $parents = Parents::with(['user', 'student'])->get();
                return ParentResource::collection($parents);

            case 'student':
                $students = Student::all();
                return StudentResource::collection($students);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid type. Use mentor, teacher, parent, or student.'
                ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $request->input('type');

        if ($type === 'student') {
            $data = $request->validate([
                'academic_year' => 'required|string',
                'level_class' => 'required|string',
                'religion_name' => 'required|string',
                'mentor_id' => 'required|exists:mentors,mentor_id',
                'name_student' => 'required|string|max:255',
                'gender' => 'required|string',
                'address' => 'required|string',
                'phone_number' => 'required|string|max:15',
            ]);

            $student = Student::create($data);
            return new StudentResource($student);
        }

        if ($type === 'teacher' || $type === 'parent') {
            $data = $request->validate([
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => ['required', Rule::in(['mentor', 'subject_teacher', 'parent'])],
                'phone_number' => 'nullable|string|max:15',
                // Teacher specific
                'name' => 'required_if:role,subject_teacher|string|max:255',
                'name_mentor' => 'required_if:role,mentor|string|max:255',
                'nip' => 'nullable|string|max:23',
                // Parent specific
                'name_parent' => 'required_if:role,parent|string|max:150',
                'student_id' => 'required_if:role,parent|exists:students,student_id',
            ]);

            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'],
                    'phone_number' => $data['phone_number'] ?? null,
                ]);

                if ($data['role'] === 'mentor') {
                    $detail = $user->mentor()->create([
                        'name_mentor' => $data['name_mentor'],
                        'nip' => $data['nip'] ?? null,
                        'phone_number' => $data['phone_number'] ?? '',
                    ]);
                    return new MentorResource($detail->load('user'));
                } elseif ($data['role'] === 'subject_teacher') {
                    $detail = $user->teacher()->create([
                        'name' => $data['name'],
                        'phone_number' => $data['phone_number'] ?? '',
                    ]);
                    return new TeacherResource($detail->load('user'));
                } elseif ($data['role'] === 'parent') {
                    $detail = $user->parent()->create([
                        'name_parent' => $data['name_parent'],
                        'student_id' => $data['student_id'],
                    ]);
                    return new ParentResource($detail->load(['user', 'student']));
                }
            });
        }

        return response()->json(['message' => 'Invalid type or missing parameters'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $type = $request->query('type');

        switch ($type) {
            case 'teacher':
                $mentor = Mentor::with('user')->find($id);
                if ($mentor) return new MentorResource($mentor);
                
                $teacher = Teacher::with('user')->find($id);
                if ($teacher) return new TeacherResource($teacher);
                break;

            case 'parent':
                $parent = Parents::with(['user', 'student'])->find($id);
                if ($parent) return new ParentResource($parent);
                break;

            case 'student':
                $student = Student::find($id);
                if ($student) return new StudentResource($student);
                break;
        }

        return response()->json(['message' => 'Resource not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $type = $request->input('type');

        if ($type === 'student') {
            $student = Student::findOrFail($id);
            $data = $request->validate([
                'academic_year' => 'sometimes|string',
                'level_class' => 'sometimes|string',
                'religion_name' => 'sometimes|string',
                'mentor_id' => 'sometimes|exists:mentors,mentor_id',
                'name_student' => 'sometimes|string|max:255',
                'gender' => 'sometimes|string',
                'address' => 'sometimes|string',
                'phone_number' => 'sometimes|string|max:15',
            ]);

            $student->update($data);
            return new StudentResource($student);
        }

        // For Teacher/Parent, we update the detail record and optionally the user
        if ($type === 'teacher' || $type === 'parent') {
            $role = $request->input('role');
            
            return DB::transaction(function () use ($request, $id, $role) {
                if ($role === 'mentor') {
                    $detail = Mentor::with('user')->findOrFail($id);
                } elseif ($role === 'subject_teacher') {
                    $detail = Teacher::with('user')->findOrFail($id);
                } elseif ($role === 'parent') {
                    $detail = Parents::with('user')->findOrFail($id);
                } else {
                    return response()->json(['message' => 'Invalid role'], 400);
                }

                $user = $detail->user;

                $userData = $request->validate([
                    'username' => ['sometimes', 'string', Rule::unique('users')->ignore($user->user_id, 'user_id')],
                    'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->user_id, 'user_id')],
                    'password' => 'sometimes|string|min:6',
                    'phone_number' => 'sometimes|nullable|string|max:15',
                ]);

                if (isset($userData['password'])) {
                    $userData['password'] = Hash::make($userData['password']);
                }

                $user->update($userData);

                if ($role === 'mentor') {
                    $detail->update($request->validate([
                        'name_mentor' => 'sometimes|string|max:255',
                        'nip' => 'sometimes|nullable|string|max:23',
                        'phone_number' => 'sometimes|string|max:15',
                    ]));
                    return new MentorResource($detail->fresh('user'));
                } elseif ($role === 'subject_teacher') {
                    $detail->update($request->validate([
                        'name' => 'sometimes|string|max:255',
                        'phone_number' => 'sometimes|string|max:15',
                    ]));
                    return new TeacherResource($detail->fresh('user'));
                } elseif ($role === 'parent') {
                    $detail->update($request->validate([
                        'name_parent' => 'sometimes|string|max:150',
                        'student_id' => 'sometimes|exists:students,student_id',
                    ]));
                    return new ParentResource($detail->fresh(['user', 'student']));
                }
            });
        }

        return response()->json(['message' => 'Invalid type'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $type = $request->query('type');
        $role = $request->query('role');

        if ($type === 'student') {
            $student = Student::findOrFail($id);
            $student->delete();
            return response()->json(['success' => true, 'message' => 'Student deleted']);
        }

        if ($type === 'teacher' || $type === 'parent') {
            return DB::transaction(function () use ($id, $role) {
                if ($role === 'mentor') {
                    $detail = Mentor::findOrFail($id);
                } elseif ($role === 'subject_teacher') {
                    $detail = Teacher::findOrFail($id);
                } elseif ($role === 'parent') {
                    $detail = Parents::findOrFail($id);
                } else {
                    return response()->json(['message' => 'Invalid role'], 400);
                }

                $user = $detail->user;
                $detail->delete();
                if ($user) {
                    $user->delete();
                }

                return response()->json(['success' => true, 'message' => 'User and associated data deleted']);
            });
        }

        return response()->json(['message' => 'Invalid type'], 400);
    }
}
