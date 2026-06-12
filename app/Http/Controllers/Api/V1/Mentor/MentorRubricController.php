<?php

namespace App\Http\Controllers\Api\V1\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\Subject;
use App\Models\RubricCategory;
use App\Models\RubricCriteria;
use App\Models\LevelClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MentorRubricController extends Controller
{
    private function authorizeMentorSubject(Mentor $mentor, int $subjectId): Subject
    {
        $subject = Subject::findOrFail($subjectId);
        $mentorClasses = LevelClass::where('mentor_id', $mentor->mentor_id)->pluck('class_id')->toArray();
        if (!in_array($subject->class_id, $mentorClasses)) {
            abort(403, 'Unauthorized. This subject does not belong to your mentored class.');
        }
        return $subject;
    }
    
    private function authorizeMentorRubric(Mentor $mentor, int $rubricId): RubricCategory
    {
        $rubric = RubricCategory::findOrFail($rubricId);
        $this->authorizeMentorSubject($mentor, $rubric->subject_id);
        return $rubric;
    }
    
    private function authorizeMentorCriteria(Mentor $mentor, int $criteriaId): RubricCriteria
    {
        $criteria = RubricCriteria::findOrFail($criteriaId);
        $this->authorizeMentorRubric($mentor, $criteria->rubric_id);
        return $criteria;
    }

    public function getSubjects(Request $request): JsonResponse
    {
        $mentor = $request->user()->mentor;
        if (!$mentor) {
            return $this->errorResponse('Mentor profile not found', 404);
        }

        $mentorClasses = LevelClass::where('mentor_id', $mentor->mentor_id)->pluck('class_id');
        $subjects = Subject::whereIn('class_id', $mentorClasses)->with('teacher')->get();

        return $this->successResponse($subjects, 'Subjects retrieved successfully.');
    }

    public function index(Request $request, int $subjectId): JsonResponse
    {
        $mentor = $request->user()->mentor;
        if (!$mentor) return $this->errorResponse('Mentor profile not found', 404);

        $this->authorizeMentorSubject($mentor, $subjectId);

        $rubrics = RubricCategory::with('criteria')
            ->where('subject_id', $subjectId)
            ->get();

        return $this->successResponse($rubrics, 'Rubrics retrieved successfully.');
    }

    public function storeCategory(Request $request, int $subjectId): JsonResponse
    {
        try {
            $mentor = $request->user()->mentor;
            if (!$mentor) return $this->errorResponse('Mentor profile not found', 404);

            $subject = $this->authorizeMentorSubject($mentor, $subjectId);
            $request->validate(['rubric_name' => 'required|string|max:100']);

            $category = RubricCategory::create([
                'subject_id'  => $subjectId,
                'teacher_id'  => $subject->teacher_id, // Link to the subject's teacher
                'rubric_name' => $request->rubric_name,
                'term'        => $subject->term,
            ]);

            return $this->successResponse($category, 'Category created successfully.', 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Failed to create category.', 500);
        }
    }

    public function updateCategory(Request $request, int $rubricId): JsonResponse
    {
        $mentor = $request->user()->mentor;
        if (!$mentor) return $this->errorResponse('Mentor profile not found', 404);

        $rubric = $this->authorizeMentorRubric($mentor, $rubricId);
        $request->validate(['rubric_name' => 'required|string|max:100']);

        $rubric->update(['rubric_name' => $request->rubric_name]);

        return $this->successResponse(null, 'Category updated successfully.');
    }

    public function destroyCategory(int $rubricId): JsonResponse
    {
        $mentor = request()->user()->mentor;
        if (!$mentor) return $this->errorResponse('Mentor profile not found', 404);

        $rubric = $this->authorizeMentorRubric($mentor, $rubricId);
        $rubric->delete();

        return $this->successResponse(null, 'Category deleted successfully.');
    }

    public function storeCriteria(Request $request, int $rubricId): JsonResponse
    {
        $mentor = $request->user()->mentor;
        if (!$mentor) return $this->errorResponse('Mentor profile not found', 404);

        $rubric = $this->authorizeMentorRubric($mentor, $rubricId);
        $request->validate([
            'criteria_name'       => 'required|string|max:255',
            'default_description' => 'nullable|string'
        ]);

        $criteria = RubricCriteria::create([
            'rubric_id'           => $rubricId,
            'criteria_name'       => $request->criteria_name,
            'default_description' => $request->default_description ?? null,
        ]);

        return $this->successResponse($criteria, 'Criteria created successfully.', 201);
    }

    public function updateCriteria(Request $request, int $criteriaId): JsonResponse
    {
        $mentor = $request->user()->mentor;
        if (!$mentor) return $this->errorResponse('Mentor profile not found', 404);

        $criteria = $this->authorizeMentorCriteria($mentor, $criteriaId);
        $request->validate([
            'criteria_name'       => 'required|string|max:255',
            'default_description' => 'nullable|string'
        ]);

        $criteria->update([
            'criteria_name'       => $request->criteria_name,
            'default_description' => $request->default_description ?? null,
        ]);

        return $this->successResponse(null, 'Criteria updated successfully.');
    }

    public function destroyCriteria(int $criteriaId): JsonResponse
    {
        $mentor = request()->user()->mentor;
        if (!$mentor) return $this->errorResponse('Mentor profile not found', 404);

        $criteria = $this->authorizeMentorCriteria($mentor, $criteriaId);
        $criteria->delete();

        return $this->successResponse(null, 'Criteria deleted successfully.');
    }
}
