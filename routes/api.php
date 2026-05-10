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

        Route::middleware('role:mentor')->prefix('mentor')->group(function (){

        });

        Route::middleware('role:teacher')->prefix('teacher')->group(function () {
            Route::get('/profile', [TeacherProfileController::class, 'show']);

            Route::get('/subjects', [TeacherSubjectController::class, 'index']);
            Route::get('/subjects/{subjectId}/students', [TeacherSubjectController::class, 'students']);

            // input request score 
            Route::get('/subjects/{subjectId}/students/{studentId}/scores', [TeacherScoreController::class, 'show']);
            Route::post('/subjects/{subjectId}/students/{studentId}/scores', [TeacherScoreController::class, 'store']);
        });
    });
});
