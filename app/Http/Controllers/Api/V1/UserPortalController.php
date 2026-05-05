<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MentorResource;
use App\Http\Resources\V1\ParentResource;
use App\Http\Resources\V1\TeacherResource;
use App\Http\Resources\V1\StudentResource;
use App\Models\ReportDetail;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;

class UserPortalController extends Controller
{
    /**
     * Mengambil Profil & Data Spesifik User yang sedang Login
     */
    public function getProfile(Request $request)
    {
        $user = $request->user(); // Mendapatkan user dari Token (Sanctum)

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        switch ($user->role) {
            case 'mentor':
                $mentor = $user->mentor;
                if (!$mentor) return response()->json(['message' => 'Mentor record not found'], 404);
                
                return response()->json([
                    'success' => true,
                    'role' => 'mentor',
                    'data' => [
                        'profile' => new MentorResource($mentor->load('user')),
                        'students' => $mentor->students ? StudentResource::collection($mentor->students) : []
                    ]
                ]);

            case 'parent':
                $parent = $user->parent;
                if (!$parent) return response()->json(['message' => 'Parent record not found'], 404);

                return response()->json([
                    'success' => true,
                    'role' => 'parent',
                    'data' => [
                        'profile' => new ParentResource($parent->load('user')),
                        'child_data' => $parent->student ? new StudentResource($parent->student) : null,
                        'reports' => $parent->student ? $parent->student->reports : []
                    ]
                ]);

            case 'teacher':
                $teacher = $user->teacher;
                if (!$teacher) return response()->json(['message' => 'Teacher record not found'], 404);

                return response()->json([
                    'success' => true,
                    'role' => 'teacher',
                    'data' => [
                        'profile' => new TeacherResource($teacher->load('user')),
                        'my_subjects' => $teacher->subjects // Daftar mapel yang diampu
                    ]
                ]);

            default:
                return response()->json(['message' => 'Role not recognized or not allowed for portal'], 403);
        }
    }

    /**
     * Guru mengambil daftar murid berdasarkan mata pelajaran yang diampu
     */
    public function getStudentsBySubject(Request $request, $subject_id)
    {
        $user = $request->user();
        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $teacher = $user->teacher;
        
        // Pastikan guru ini memang mengajar mapel tersebut
        $subject = $teacher->subjects()->where('subjects.subject_id', $subject_id)->first();
        if (!$subject) {
            return response()->json(['message' => 'You do not teach this subject'], 403);
        }

        // Ambil murid (bisa disesuaikan logic pengambilannya, misal berdasarkan kelas)
        // Di sini saya ambil semua murid sebagai contoh awal
        $students = Student::all(); 

        return response()->json([
            'success' => true,
            'subject' => $subject->name_subject,
            'students' => StudentResource::collection($students)
        ]);
    }

    /**
     * Guru menginput nilai untuk murid tertentu pada mapel tertentu
     */
    public function submitStudentScore(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'report_id' => 'required|exists:reports,report_id',
            'subject_id' => 'required|exists:subjects,subject_id',
            'score' => 'required|numeric|between:0,100',
            'description' => 'nullable|string'
        ]);

        $teacher = $user->teacher;
        
        // Validasi guru mengajar mapel ini
        if (!$teacher->subjects()->where('subjects.subject_id', $data['subject_id'])->exists()) {
            return response()->json(['message' => 'You do not teach this subject'], 403);
        }

        // Simpan nilai
        $reportDetail = ReportDetail::updateOrCreate(
            ['report_id' => $data['report_id'], 'subject_id' => $data['subject_id']],
            [
                'score' => $data['score'],
                'description_subject' => $data['description'] ?? '-'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Score submitted successfully',
            'data' => $reportDetail
        ]);
    }

    /**
     * Mengecek status autentikasi dan mengembalikan daftar izin (permissions)
     * Sangat berguna untuk RBAC di Frontend Next.js
     */
    public function checkAuth(Request $request)
    {
        $user = $request->user();
        $permissions = [];
        
        switch ($user->role) {
            case 'admin':
                $permissions = ['manage_users', 'manage_students', 'manage_subjects', 'view_all_reports'];
                break;
            case 'teacher':
                $permissions = ['view_my_subjects', 'input_scores'];
                break;
            case 'mentor':
                $permissions = ['view_class_students', 'view_reports'];
                break;
            case 'parent':
                $permissions = ['view_child_report'];
                break;
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'permissions' => $permissions
        ]);
    }
}
