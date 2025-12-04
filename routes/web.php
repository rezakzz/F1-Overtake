<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\landingController;
use App\Http\Controllers\AdminController;

Route::name('admin.')->prefix('admin')->group(function(){
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name("dashboard");
});

Route::get('Katalog/{slug}', [landingController::class, 'Katalog'])->name('landing.Katalog');

Route::resource('/', landingController::class);
