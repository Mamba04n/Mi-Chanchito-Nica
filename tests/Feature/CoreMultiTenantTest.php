<?php

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Ensure roles are seeded (they usually are via DatabaseSeeder, but let's be safe)
    $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
});

test('a user cannot access a company they do not belong to', function () {
    $companyA = Company::create(['name' => 'Company A', 'country_code' => 'NI', 'currency_code' => 'NIO']);
    $companyB = Company::create(['name' => 'Company B', 'country_code' => 'NI', 'currency_code' => 'NIO']);

    $userA = User::factory()->create();
    $ownerRole = Role::where('key', 'owner')->first();
    
    $companyA->users()->attach($userA, ['role_id' => $ownerRole->id]);

    $this->actingAs($userA);

    // Context should be able to switch to Company A
    session(['active_company_id' => $companyA->id]);
    $response = $this->get('/dashboard');
    $response->assertStatus(200);

    // But should not be able to switch to Company B
    session(['active_company_id' => $companyB->id]);
    $response = $this->get('/dashboard');
    $response->assertStatus(403);
});

test('owner has all permissions', function () {
    $company = Company::create(['name' => 'Company A']);
    $user = User::factory()->create();
    $ownerRole = Role::where('key', 'owner')->first();
    
    $company->users()->attach($user, ['role_id' => $ownerRole->id]);

    expect($user->hasPermission('inventory.view', $company))->toBeTrue();
    expect($user->hasPermission('any.random.permission', $company))->toBeTrue();
});

test('viewer role lacks critical permissions', function () {
    $company = Company::create(['name' => 'Company A']);
    $user = User::factory()->create();
    $viewerRole = Role::where('key', 'viewer')->first();
    
    $company->users()->attach($user, ['role_id' => $viewerRole->id]);

    expect($user->hasPermission('inventory.view', $company))->toBeTrue(); // Viewer can view
    expect($user->hasPermission('inventory.create', $company))->toBeFalse(); // But cannot create
});
