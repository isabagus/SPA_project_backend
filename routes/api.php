<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StudentApiController;
use App\Http\Controllers\Api\V1\UserManagementController;
use App\Http\Controllers\Api\V1\UserPortalController;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    // Public Routes
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);

        // Portal Utama untuk Next.js (Teacher, Parent, Mentor)
        Route::get('/auth/check', [UserPortalController::class, 'checkAuth']);

        Route::middleware('role:teacher|mentor|parent')->group(function () {
            Route::get('report-card', [UserPortalController::class, 'getReportCard']);
        });

        Route::middleware('role:teacher')->group(function () {
            Route::get('/portal/teacher/students/{subject_id}', [UserPortalController::class, 'getStudentsBySubject']);
            Route::post('/portal/teacher/submit-score', [UserPortalController::class, 'submitStudentScore']);
        });

        // Student Management (jika diakses via API)
        Route::apiResource('users-management', UserManagementController::class);
    });
    Route::apiResource('students', StudentApiController::class);
});
