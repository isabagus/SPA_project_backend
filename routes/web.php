<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SubjectController;


Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/template', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class);
<<<<<<< HEAD
});

require __DIR__ . '/auth.php';
=======
    Route::resource('subjects', SubjectController::class);
  
});
>>>>>>> 54c2d1b8e3839cac0a07e8feb8d33836ea605ce2
