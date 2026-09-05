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
        Route::get('/dashboard', \App\Livewire\Dashboard\Dashboard::class)->name('dashboard');
        
        // Inventario
        Route::prefix('inventory')->name('inventory.')->group(function() {
            Route::get('/', \App\Livewire\Inventory\ProductStockList::class)->name('index');
            Route::get('/warehouses', \App\Livewire\Inventory\WarehouseList::class)->name('warehouses');
            Route::get('/movements', \App\Livewire\Inventory\MovementList::class)->name('movements');
            Route::get('/kardex', \App\Livewire\Inventory\Kardex::class)->name('kardex');
            Route::get('/adjustments', \App\Livewire\Inventory\StockAdjustment::class)->name('adjustments');
            Route::get('/transfers', \App\Livewire\Inventory\StockTransfer::class)->name('transfers');
        });

        // Catálogo Base
        Route::prefix('catalog')->name('catalog.')->group(function() {
            Route::get('/products', \App\Livewire\Catalog\ProductList::class)->name('products');
            Route::get('/services', \App\Livewire\Catalog\ProductList::class)->name('services'); // Same component? We'll see.
            Route::get('/categories', \App\Livewire\Catalog\CategoryList::class)->name('categories');
            Route::get('/units', \App\Livewire\Catalog\UnitList::class)->name('units');
        });

        // Clientes
        Route::prefix('customers')->name('customers.')->group(function() {
            Route::get('/', \App\Livewire\Customers\CustomerList::class)->name('index');
        });

        // Proveedores
        Route::prefix('suppliers')->name('suppliers.')->group(function() {
            Route::get('/', \App\Livewire\Suppliers\SupplierList::class)->name('index');
        });

        // Facturación
        Route::prefix('billing')->name('billing.')->group(function() {
            Route::get('/invoices', \App\Livewire\Billing\InvoiceList::class)->name('invoices.index');
            Route::get('/invoices/create', \App\Livewire\Billing\InvoiceForm::class)->name('invoices.create');
            Route::get('/invoices/{id}/edit', \App\Livewire\Billing\InvoiceForm::class)->name('invoices.edit');
            Route::get('/invoices/{id}', \App\Livewire\Billing\InvoiceDetail::class)->name('invoices.show');
        });

        // Receivables (CxC)
        Route::prefix('receivables')->name('receivables.')->group(function() {
            Route::get('/', \App\Livewire\Receivables\ReceivableDashboard::class)->name('index');
            Route::get('/list', \App\Livewire\Receivables\ReceivableList::class)->name('list');
        });

        // Compras
        Route::prefix('purchases')->name('purchases.')->group(function() {
            Route::get('/', \App\Livewire\Purchases\PurchaseList::class)->name('index');
            Route::get('/create', \App\Livewire\Purchases\PurchaseForm::class)->name('create');
            Route::get('/{id}/edit', \App\Livewire\Purchases\PurchaseForm::class)->name('edit');
            Route::get('/{id}', \App\Livewire\Purchases\PurchaseDetail::class)->name('show');
        });

        // Payables (CxP)
        Route::prefix('payables')->name('payables.')->group(function() {
            Route::get('/', \App\Livewire\Payables\PayableDashboard::class)->name('index');
            Route::get('/list', \App\Livewire\Payables\PayableList::class)->name('list');
        });

        // Treasury
        Route::prefix('treasury')->name('treasury.')->group(function() {
            Route::get('/', \App\Livewire\Treasury\Dashboard::class)->name('index');
            Route::get('/accounts', \App\Livewire\Treasury\AccountList::class)->name('accounts');
            Route::get('/movements', \App\Livewire\Treasury\MovementList::class)->name('movements');
        });

        // Educación
        Route::prefix('education')->name('education.')->group(function() {
            Route::get('/', \App\Livewire\Education\Home::class)->name('index');
            Route::get('/explore', \App\Livewire\Education\Explore::class)->name('explore');
            Route::get('/my-learning', \App\Livewire\Education\MyLearning::class)->name('my-learning');
            Route::get('/programs/{id}', \App\Livewire\Education\ProgramDetail::class)->name('programs.show');
            Route::get('/lessons/{id}', \App\Livewire\Education\LessonViewer::class)->name('lessons.show');
            Route::get('/assessments/{id}', \App\Livewire\Education\AssessmentViewer::class)->name('assessments.show');
        });

        // Gamificación
        Route::prefix('gamification')->name('gamification.')->group(function() {
            Route::get('/', \App\Livewire\Gamification\Dashboard::class)->name('index');
        });

        // Settings (Módulos y Usuarios)
        Route::prefix('settings')->name('settings.')->group(function() {
            Route::get('/modules', \App\Livewire\Settings\Modules\ModuleManager::class)->name('modules');
            Route::get('/users', \App\Livewire\Settings\Users\ManageUsers::class)->name('users');
            Route::get('/company', \App\Livewire\Settings\Company\EditCompany::class)->name('company.edit');
        });
    });
});

require __DIR__.'/auth.php';
