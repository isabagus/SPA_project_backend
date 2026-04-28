<?php

use Illuminate\Support\Facades\Route;

Route::get('/template', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.dashboard.index');
    });
    Route::get('/dashboard', function () {
        return view('layouts.dashboard.index');
    });
    Route::get('/dashboard', function () {
        return view('layouts.dashboard.index');
    });
});
