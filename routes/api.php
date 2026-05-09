<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StudentApiController;
use App\Http\Controllers\Api\V1\UserManagementController;
use App\Http\Controllers\Api\V1\UserPortalController;
use App\Http\Controllers\Api\V1\AuthController;

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

        Route::middleware('role:teacher|mentor|parent')->group(function () {
            Route::get('report-card', [UserPortalController::class, 'getReportCard']);
        });

        Route::middleware('role:teacher')->group(function () {
            Route::get('/portal/teacher/students/{subject_id}', [UserPortalController::class, 'getStudentsBySubject']);
            Route::post('/portal/teacher/submit-score', [UserPortalController::class, 'submitStudentScore']);
        });

        Route::apiResource('students', StudentApiController::class);
        Route::apiResource('users-management', UserManagementController::class);
    });
});
