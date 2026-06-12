<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StudentApiController;
use App\Http\Controllers\Api\V1\UserManagementController;
use App\Http\Controllers\Api\V1\UserPortalController;
use App\Http\Controllers\Api\V1\AuthController;

// Controller Baru untuk Teacher
use App\Http\Controllers\Api\V1\Teacher\TeacherProfileController;
use App\Http\Controllers\Api\V1\Teacher\TeacherSubjectController;
use App\Http\Controllers\Api\V1\Teacher\TeacherScoreController;
use App\Http\Controllers\Api\V1\Teacher\TeacherRubricController;
use App\Http\Controllers\Api\V1\Parent\ParentStudentController;
use App\Http\Controllers\Api\V1\Parent\ParentReportController;

use App\Http\Controllers\V1\MentorController;
use App\Http\Controllers\Api\V1\Mentor\MentorRubricController;

Route::prefix('v1')->group(function () {
    // Public Routes

    // Protected Routes
    
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/auth/check', [UserPortalController::class, 'checkAuth']);

        // General report PDF export route for all authenticated API users (Parent, Mentor, Teacher)
        Route::get('/reports/{reportId}/export', [ParentReportController::class, 'exportPdf']);
        Route::get('/reports/{reportId}', [ParentReportController::class, 'showReport']);


        Route::middleware('role:mentor')->prefix('mentor')->group(function (){
            Route::get('/classes', [MentorController::class, 'getClasses']);
            Route::get('/students', [MentorController::class, 'getStudents']);
            Route::get('/students/{studentId}/evaluation-form', [MentorController::class, 'getEvaluationForm']);
            Route::post('/students/{studentId}/evaluation', [MentorController::class, 'submitEvaluation']);
            Route::get('/students/{studentId}/academic-report', [MentorController::class, 'getAcademicReport']);
            Route::get('/students/{studentId}/academic-report/{reportId}', [MentorController::class, 'getSubjectDetail']);
            Route::put('/students/{studentId}/academic-report/detail/{detailId}', [MentorController::class, 'updateReportDetail']);

            // Master Rubrik Management (Mentor Access)
            Route::get('/subjects', [MentorRubricController::class, 'getSubjects']);
            Route::get('/subjects/{subjectId}/rubrics', [MentorRubricController::class, 'index']);
            Route::post('/subjects/{subjectId}/rubrics', [MentorRubricController::class, 'storeCategory']);
            Route::put('/rubrics/{rubricId}', [MentorRubricController::class, 'updateCategory']);
            Route::delete('/rubrics/{rubricId}', [MentorRubricController::class, 'destroyCategory']);

            Route::post('/rubrics/{rubricId}/criteria', [MentorRubricController::class, 'storeCriteria']);
            Route::put('/criteria/{criteriaId}', [MentorRubricController::class, 'updateCriteria']);
            Route::delete('/criteria/{criteriaId}', [MentorRubricController::class, 'destroyCriteria']);
        });

        Route::middleware('role:teacher')->prefix('teacher')->group(function () {
            Route::get('/profile', [TeacherProfileController::class, 'show']);

            Route::get('/subjects', [TeacherSubjectController::class, 'index']);
            Route::get('/subjects/{subjectId}/students', [TeacherSubjectController::class, 'students']);

            // input request score 
            Route::get('/subjects/{subjectId}/students/{studentId}/scores', [TeacherScoreController::class, 'show']);
            Route::post('/subjects/{subjectId}/students/{studentId}/scores', [TeacherScoreController::class, 'store']);

            // Master Rubrik Management (Read-Only)
            Route::get('/subjects/{subjectId}/rubrics', [TeacherRubricController::class, 'index']);
            // Teacher is not allowed to mutate rubrics anymore
        });

        Route::middleware('role:parent')->prefix('parent')->group(function () {
            Route::get('/children', [ParentStudentController::class, 'index']);
            Route::get('/children/{studentId}/reports', [ParentReportController::class, 'index']);
            Route::get('/children/report/{reportId}', [ParentReportController::class, 'show']);
            Route::get('/children/report/{reportId}/export', [ParentReportController::class, 'exportPdf']);
        });
    });
});
