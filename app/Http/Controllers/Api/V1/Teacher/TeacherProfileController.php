<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Teacher\TeacherProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherProfileController extends Controller
{
    /**
     * GET /api/v1/teacher/profile
     *
     * Menampilkan profil lengkap guru yang sedang login.
     */
    public function show(Request $request): JsonResponse
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return $this->errorResponse('Teacher profile not found.', 404);
        }
        
        $teacher->load('user');
        return $this->successResponse(
            new TeacherProfileResource($teacher),
            'Teacher profile retrieved successfully.'
        );
    }
}
