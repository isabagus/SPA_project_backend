<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherRubricController extends Controller
{
    public function __construct(
        private readonly TeacherService $teacherService
    ) {}

    public function index(int $subjectId): JsonResponse
    {
        $teacher = request()->user()->teacher;
        if (!$teacher) return $this->errorResponse('Teacher not found', 404);

        $rubrics = $this->teacherService->getMyRubrics($teacher, $subjectId);

        return $this->successResponse($rubrics, 'Rubrics retrieved successfully.');
    }

    public function storeCategory(Request $request, int $subjectId): JsonResponse
    {
        try {
            $teacher = $request->user()->teacher;
            $request->validate(['rubric_name' => 'required|string|max:100']);

            $category = $this->teacherService->storeCategory($teacher, $subjectId, $request->all());

            return $this->successResponse($category, 'Category created successfully.', 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Failed to create category.', 500);
        }
    }

    public function updateCategory(Request $request, int $rubricId): JsonResponse
    {
        $teacher = $request->user()->teacher;
        $request->validate(['rubric_name' => 'required|string|max:100']);

        $this->teacherService->updateCategory($teacher, $rubricId, $request->all());

        return $this->successResponse(null, 'Category updated successfully.');
    }

    public function destroyCategory(int $rubricId): JsonResponse
    {
        $teacher = request()->user()->teacher;
        $this->teacherService->destroyCategory($teacher, $rubricId);

        return $this->successResponse(null, 'Category deleted successfully.');
    }

    public function storeCriteria(Request $request, int $rubricId): JsonResponse
    {
        $teacher = $request->user()->teacher;
        $request->validate([
            'criteria_name'       => 'required|string|max:255',
            'default_description' => 'nullable|string'
        ]);

        $criteria = $this->teacherService->storeCriteria($teacher, $rubricId, $request->all());

        return $this->successResponse($criteria, 'Criteria created successfully.', 201);
    }

    public function updateCriteria(Request $request, int $criteriaId): JsonResponse
    {
        $teacher = $request->user()->teacher;
        $request->validate([
            'criteria_name'       => 'required|string|max:255',
            'default_description' => 'nullable|string'
        ]);

        $this->teacherService->updateCriteria($teacher, $criteriaId, $request->all());

        return $this->successResponse(null, 'Criteria updated successfully.');
    }

    public function destroyCriteria(int $criteriaId): JsonResponse
    {
        $teacher = request()->user()->teacher;
        $this->teacherService->destroyCriteria($teacher, $criteriaId);

        return $this->successResponse(null, 'Criteria deleted successfully.');
    }
}
