<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MentorController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ParentController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard.index');
    });

    // Auth Routes
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('auth');
    });

    // Protected Admin Routes
    Route::middleware(['auth'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::middleware(['role:admin'])->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
            Route::resource('users', UserController::class);
            Route::resource('students', StudentController::class);
            Route::get('subjects/{id}/assign-teachers', [SubjectController::class, 'assignTeachers'])->name('subjects.assignTeachers');
            Route::put('subjects/{id}/assign-teachers', [SubjectController::class, 'updateTeachers'])->name('subjects.updateTeachers');
            Route::resource('subjects', SubjectController::class);
            Route::resource('teachers', TeacherController::class);
            
            Route::get('mentors/set-class', [MentorController::class, 'showSetClassView'])->name('mentors.setClass');
            Route::put('mentors/set-class/set', [MentorController::class, 'updateSetClass'])->name('mentors.updateSetClass');
            Route::resource('mentors', MentorController::class);
            Route::resource('parents', ParentController::class);
            Route::resource('reports', ReportController::class);
        });
    });
});

require __DIR__ . '/auth.php';

// Route::get('/template', function () {
//     return view('welcome');
// });