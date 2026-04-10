<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'summary']);

Route::resource('tasks', TaskController::class);