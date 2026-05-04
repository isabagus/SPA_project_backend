<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StudentApiController;
use App\Http\Controllers\Api\V1\Admin\AdminManagementController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::apiResource('students', StudentApiController::class);
    Route::apiResource('management', AdminManagementController::class);
    
    // Admin Management
    Route::prefix('admin')->group(function () {
    });
});
    
