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
        
        // Inventario
        Route::prefix('inventory')->name('inventory.')->group(function() {
            Route::get('/', \App\Livewire\Inventory\ProductStockList::class)->name('index');
            Route::get('/warehouses', \App\Livewire\Inventory\WarehouseList::class)->name('warehouses');
            Route::get('/movements', \App\Livewire\Inventory\MovementList::class)->name('movements');
            Route::get('/kardex', \App\Livewire\Inventory\Kardex::class)->name('kardex');
            Route::get('/adjustments', \App\Livewire\Inventory\StockAdjustment::class)->name('adjustments');
            Route::get('/transfers', \App\Livewire\Inventory\StockTransfer::class)->name('transfers');
        });
    });
});

require __DIR__.'/auth.php';
