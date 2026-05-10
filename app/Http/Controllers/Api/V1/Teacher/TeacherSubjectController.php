<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Teacher\SubjectListResource;
use App\Http\Resources\V1\Teacher\StudentWithScoreResource;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function __construct(
        private readonly TeacherService $teacherService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return $this->errorResponse('Teacher profile not found.', 404);
        }

        $subjects = $this->teacherService->getMySubjects($teacher);

        return $this->successResponse(SubjectListResource::collection($subjects),'Subject list retrieved successfully.');
    }

    public function students(Request $request, int $subjectId): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return $this->errorResponse('Teacher profile not found.', 404);
        }

        $result = $this->teacherService->getStudentsWithScore($teacher, $subjectId);

        if (!$result['authorized']) {
            return $this->errorResponse(
                'You are not authorized to access this subject.',
                403
            );
        }

        return $this->successResponse([
            'subject'  => new SubjectListResource($result['subject']),
            'students' => StudentWithScoreResource::collection($result['students']),
        ], 'Students retrieved successfully.');
    }
}
