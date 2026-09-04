<?php

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Services\ModuleManager;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'ModuleSeeder']);
});

test('activating a module registers it and logs audit event', function () {
    $company = Company::create(['name' => 'Company A']);
    
    $manager = app(ModuleManager::class);
    
    // Activating catalog (has no dependencies)
    $manager->activateModule($company, 'catalog');
    
    expect($manager->isActive($company, 'catalog'))->toBeTrue();

    // Check audit log
    $this->assertDatabaseHas('audit_logs', [
        'company_id' => $company->id,
        'action' => 'module.enabled',
    ]);
});

test('cannot activate module without dependencies', function () {
    $company = Company::create(['name' => 'Company A']);
    $manager = app(ModuleManager::class);
    
    // Billing requires catalog and customers
    // This should throw an exception
    $manager->activateModule($company, 'billing');
})->throws(\Exception::class, "No se puede activar 'Facturación'. Requiere el módulo: customers");

test('cannot deactivate module if it is a dependency', function () {
    $company = Company::create(['name' => 'Company A']);
    $manager = app(ModuleManager::class);
    
    $manager->activateModule($company, 'catalog');
    $manager->activateModule($company, 'customers');
    $manager->activateModule($company, 'billing'); // Depends on customers and catalog

    expect($manager->isActive($company, 'billing'))->toBeTrue();

    // Try to disable catalog, should fail because billing depends on it
    $manager->deactivateModule($company, 'catalog');
})->throws(\Exception::class, "No se puede desactivar 'Catálogo'. El módulo 'Facturación' depende de él.");
