<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/template', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class);
    Route::resource('subjects', SubjectController::class);
  
});