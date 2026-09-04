<?php

use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'start'])->name('onboarding.start');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::view('profile', 'profile')->name('profile');

    Route::middleware(\App\Http\Middleware\CompanyScope::class)->group(function () {
        Route::view('/dashboard', 'dashboard')->name('dashboard');
    });
});

require __DIR__.'/auth.php';
