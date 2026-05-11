<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Teacher\ScoreFormResource;
use App\Http\Requests\Teacher\SubmitScoreRequest;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;

class TeacherScoreController extends Controller
{
    public function __construct(
        private readonly TeacherService $teacherService
    ) {}

    public function show(int $subjectId, int $studentId): JsonResponse
    {
        $teacher = request()->user()->teacher;

        if (!$teacher) {
            return $this->errorResponse('Teacher profile not found.', 404);
        }

        $result = $this->teacherService->getScoreForm($teacher, $subjectId, $studentId);

        if (!$result['authorized']) {
            return $this->errorResponse('Unauthorized to access this subject or student not found.', 403);
        }

        return $this->successResponse(
            new ScoreFormResource($result),
            'Score form retrieved successfully.'
        );
    }

    /**
     * POST /api/v1/teacher/subjects/{subjectId}/students/{studentId}/scores
     *
     * Menyimpan atau mengupdate nilai siswa per rubrik dan merubah nilai average di raport.
     */
    public function store(SubmitScoreRequest $request, int $subjectId, int $studentId): JsonResponse
    {
        try {
            $teacher = $request->user()->teacher;

        if (!$teacher) {
            return $this->errorResponse('Teacher profile not found.', 404);
        }

        $report = $this->teacherService->submitScore($teacher, $subjectId, $studentId, $request->validated());

        if (!$report) {
            return $this->errorResponse('Failed to submit score. Data validation failed or unauthorized.', 400);
        }

        return $this->successResponse(
            [
                'report_id'     => $report->report_id,
                'average_value' => $report->average_value,
            ],
            'Scores submitted successfully.'
        );
        } catch (\Throwable $th) {
            logger('error', [$th->getMessage()]);
            return $this->errorResponse('Failed to submit score. Data validation failed or unauthorized.', 400);
        }
    }
}
