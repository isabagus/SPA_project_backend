<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StudentApiController;
use App\Http\Controllers\Api\V1\UserManagementController;
use App\Http\Controllers\Api\V1\UserPortalController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::apiResource('students', StudentApiController::class);
    
    // Portal Utama untuk Next.js (Teacher, Parent, Mentor)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/check', [UserPortalController::class, 'checkAuth']);

        Route::middleware('role:teacher|mentor|parent')->group(function () {
            Route::get('/portal/profile', [UserPortalController::class, 'getProfile']);
        });

        // Khusus Guru (Input Nilai)
        Route::middleware('role:subject_teacher')->group(function () {
            Route::get('/portal/teacher/students/{subject_id}', [UserPortalController::class, 'getStudentsBySubject']);
            Route::post('/portal/teacher/submit-score', [UserPortalController::class, 'submitStudentScore']);
        });

        // Contoh jika butuh route khusus Mentor atau Parent kedepannya:
        // Route::middleware('role:mentor')->group(...);
        // Route::middleware('role:parent')->group(...);
    });
    Route::apiResource('users-management', UserManagementController::class);
});
    
