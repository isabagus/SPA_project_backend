<?php

namespace App\Http\Controllers\Api\V1\Parent;

use App\Http\Controllers\Controller;
use App\Models\Reports;
use App\Models\Parents;
use App\Models\Mentor;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ParentReportController extends Controller
{
    /**
     * Mengambil ringkasan raport untuk siswa tertentu.
     */
    public function index(Request $request, $studentId)
    {
        $user = Auth::user();
        
        // Security Check: Pastikan siswa ini memang anak dari parent yang login
        $isParent = Parents::where('user_id', $user->user_id)
            ->where('student_id', $studentId)
            ->exists();

        if (!$isParent) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Siswa ini bukan bagian dari akun Anda.'
            ], 403);
        }

        // Ambil semua raport mata pelajaran untuk siswa tersebut
        $query = Reports::where('student_id', $studentId)
            ->with([
                'subject', 
                'reportDetails.rubric', 
                'reportDetails.criteria'
            ]);

        // Opsional: Filter berdasarkan tahun ajaran jika dikirim dari frontend
        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $reports = $query->get();

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Mengambil detail satu raport mata pelajaran.
     */
    public function show($reportId)
    {
        $user = Auth::user();
        
        $report = Reports::with([
            'student', 
            'subject', 
            'reportDetails.rubric', 
            'reportDetails.criteria'
        ])->findOrFail($reportId);

        // Security Check: Pastikan raport ini milik anak dari parent yang login
        $isParent = Parents::where('user_id', $user->user_id)
            ->where('student_id', $report->student_id)
            ->exists();

        if (!$isParent) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Mengambil detail raport secara JSON untuk user terautentikasi (Parent, Mentor, Teacher, Admin).
     */
    public function showReport($reportId)
    {
        $user = Auth::user();
        
        $report = Reports::with([
            'student', 
            'subject.teacher', 
            'reportDetails.criteria.category.teacher',
            'reportDetails.criteria.category.subject',
            'reportDetails.rubric.teacher', 
            'reportDetails.rubric.subject',
            'reportDetails.criteria'
        ])->findOrFail($reportId);

        // Security Check berdasarkan role
        if ($user->role === 'parent') {
            $isParent = Parents::where('user_id', $user->user_id)
                ->where('student_id', $report->student_id)
                ->exists();
            if (!$isParent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 403);
            }
        } elseif ($user->role === 'mentor') {
            $mentor = Mentor::where('user_id', $user->user_id)->first();
            if (!$mentor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 403);
            }
            $isMentor = ($report->student->mentor_id === $mentor->mentor_id) || 
                         (\App\Models\LevelClass::where('class_id', $report->student->class_id)
                                                ->where('mentor_id', $mentor->mentor_id)
                                                ->exists());
            if (!$isMentor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 403);
            }
        } elseif ($user->role === 'teacher') {
            $isTeacher = Teacher::where('user_id', $user->user_id)
                ->where('teacher_id', $report->subject->teacher_id)
                ->exists();
            if (!$isTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 403);
            }
        } elseif ($user->role === 'admin') {
            // Admin can access anything
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Export raport ke PDF.
     */
    public function exportPdf($reportId)
    {
        $user = Auth::user();
        
        $report = Reports::with([
            'student', 
            'subject.teacher', 
            'reportDetails.criteria.category.teacher',
            'reportDetails.criteria.category.subject',
            'reportDetails.rubric.teacher', 
            'reportDetails.rubric.subject', 
            'reportDetails.criteria'
        ])->findOrFail($reportId);

        // Security Check berdasarkan role
        if ($user->role === 'parent') {
            $isParent = Parents::where('user_id', $user->user_id)
                ->where('student_id', $report->student_id)
                ->exists();
            if (!$isParent) {
                abort(403, 'Unauthorized.');
            }
        } elseif ($user->role === 'mentor') {
            $mentor = Mentor::where('user_id', $user->user_id)->first();
            if (!$mentor) {
                abort(403, 'Unauthorized.');
            }
            $isMentor = ($report->student->mentor_id === $mentor->mentor_id) || 
                         (\App\Models\LevelClass::where('class_id', $report->student->class_id)
                                                ->where('mentor_id', $mentor->mentor_id)
                                                ->exists());
            if (!$isMentor) {
                abort(403, 'Unauthorized.');
            }
        } elseif ($user->role === 'teacher') {
            $isTeacher = Teacher::where('user_id', $user->user_id)
                ->where('teacher_id', $report->subject->teacher_id)
                ->exists();
            if (!$isTeacher) {
                abort(403, 'Unauthorized.');
            }
        } elseif ($user->role === 'admin') {
            // Admin can download anything
        } else {
            abort(403, 'Unauthorized.');
        }

        $pdf = Pdf::loadView('pdf.student_report', compact('report'));
        
        $filename = 'Report-' . $report->student->name_student . '-' . $report->subject->category_subject . '.pdf';
        
        return $pdf->download($filename);
    }
}
