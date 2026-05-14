<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\MentorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorController extends Controller
{
    protected $mentorService;

    public function __construct(MentorService $mentorService)
    {
        $this->mentorService = $mentorService;
    }

    /**
     * Get students for the authenticated mentor's class
     */
    public function getStudents(Request $request)
    {
        $user = Auth::user();
        if (!$user->mentor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $levelClass = $request->query('level_class');
        
        // Jika tidak ada level_class, ambil kelas pertama yang dimiliki mentor
        if (!$levelClass) {
            $classes = $this->mentorService->getMentorClasses($user->mentor);
            if ($classes->isEmpty()) {
                return response()->json(['data' => []]);
            }
            $levelClass = $classes->first()->level_class;
        }

        $students = $this->mentorService->getStudentsInClass($user->mentor, $levelClass);

        return response()->json([
            'data' => [
                'students' => $students,
                'current_class' => $levelClass
            ]
        ]);
    }

    /**
     * Get mentor's assigned classes
     */
    public function getClasses()
    {
        $user = Auth::user();
        if (!$user->mentor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $classes = $this->mentorService->getMentorClasses($user->mentor);

        return response()->json(['data' => $classes]);
    }

    /**
     * Get student's academic overview for mentor
     */
    public function getAcademicReport(Request $request, $studentId)
    {
        $user = Auth::user();
        if (!$user->mentor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reports = $this->mentorService->getStudentAcademicReport($user->mentor->mentor_id, $studentId);

        return response()->json([
            'success' => true,
            'data'    => $reports
        ]);
    }

    /**
     * Get detailed subject report for mentor (Read-only)
     */
    public function getSubjectDetail(Request $request, $studentId, $reportId)
    {
        $user = Auth::user();
        if (!$user->mentor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $report = $this->mentorService->getSubjectDetail($user->mentor->mentor_id, $studentId, $reportId);

        return response()->json([
            'success' => true,
            'data'    => $report
        ]);
    }

    /**
     * Get mentor evaluation form (Notes + Fallback Rubrics)
     */
    public function getEvaluationForm(Request $request, $studentId)
    {
        $user = Auth::user();
        if (!$user->mentor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $levelClass = $request->query('level_class');
        if (!$levelClass) {
            return response()->json(['message' => 'level_class is required'], 400);
        }

        $formData = $this->mentorService->getEvaluationForm($user->mentor, $studentId, $levelClass);

        return response()->json(['data' => $formData]);
    }

    /**
     * Submit mentor evaluation (description + fallback scores)
     */
    public function submitEvaluation(Request $request, $studentId)
    {
        $user = Auth::user();
        if (!$user->mentor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'level_class' => 'required|string',
            'mentor_note' => 'nullable|string',
            'scores'      => 'nullable|array',
        ]);

        $report = $this->mentorService->submitEvaluation(
            $user->mentor,
            $studentId,
            $request->level_class,
            $request->all()
        );

        if (!$report) {
            return response()->json(['message' => 'Failed to save evaluation'], 500);
        }

        return response()->json([
            'message' => 'Evaluation saved successfully',
            'data' => $report
        ]);
    }

    /**
     * Update qualitative description for a specific rubric criteria (Mentor Access)
     */
    public function updateReportDetail(Request $request, $studentId, $detailId)
    {
        $user = Auth::user();
        if (!$user->mentor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'description' => 'required|string',
            'subject_id'  => 'nullable|integer',
            'criteria_id' => 'nullable|integer'
        ]);

        $detail = $this->mentorService->updateReportDetailDescription(
            $user->mentor->mentor_id,
            $studentId,
            $detailId,
            $request->description,
            $request->subject_id,
            $request->criteria_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Description updated successfully',
            'data'    => $detail
        ]);
    }
}
