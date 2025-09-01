<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('pages.dashboard.index', [
        'title' => 'Dashboard',
    ]);
})->middleware('auth')->name('dashboard');