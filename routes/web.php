<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/template', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('user', UserController::class);
  
});
