<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->to('/dashboard');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
});

Route::get('/tasks', function () {
    return Inertia::render('TasksList');
});