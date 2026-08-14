<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('gentelella')->name('gentelella.')->group(function () {
    Route::get('/', function () {
        return view('gentelella.dashboard');
    })->name('dashboard');
});
