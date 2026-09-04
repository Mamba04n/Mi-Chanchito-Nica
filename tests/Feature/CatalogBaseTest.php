<?php

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasure;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
});

test('a user can only view their own company catalog', function () {
    $companyA = Company::create(['name' => 'Company A']);
    $companyB = Company::create(['name' => 'Company B']);

    $userA = User::factory()->create();
    $ownerRole = Role::where('key', 'owner')->first();
    $companyA->users()->attach($userA, ['role_id' => $ownerRole->id]);

    // Create a customer in A
    session(['active_company_id' => $companyA->id]);
    Customer::factory()->create(['name' => 'Customer A']);

    // Create a customer in B
    session(['active_company_id' => $companyB->id]);
    Customer::factory()->create(['name' => 'Customer B']);

    // Authenticate as User A and query Customers
    $this->actingAs($userA);
    session(['active_company_id' => $companyA->id]);
    
    $customers = Customer::all();
    
    expect($customers)->toHaveCount(1);
    expect($customers->first()->name)->toBe('Customer A');
});

test('sku is unique per company but can be duplicated across companies', function () {
    $companyA = Company::create(['name' => 'Company A']);
    $companyB = Company::create(['name' => 'Company B']);

    session(['active_company_id' => $companyA->id]);
    Product::factory()->create(['sku' => 'SKU-001', 'name' => 'Prod 1']);
    
    // Duplicate SKU in Company A should fail
    try {
        Product::factory()->create(['sku' => 'SKU-001', 'name' => 'Prod 2']);
        $this->fail('Should have thrown a duplicate constraint exception.');
    } catch (QueryException $e) {
        expect($e->getCode())->toBe('23000'); // Integrity constraint violation
    }

    // Same SKU in Company B should succeed
    session(['active_company_id' => $companyB->id]);
    $productB = Product::factory()->create(['sku' => 'SKU-001', 'name' => 'Prod B']);
    
    expect($productB->sku)->toBe('SKU-001');
});

test('a viewer cannot create products', function () {
    $company = Company::create(['name' => 'Company A']);
    $user = User::factory()->create();
    $viewerRole = Role::where('key', 'viewer')->first();
    $company->users()->attach($user, ['role_id' => $viewerRole->id]);

    $this->actingAs($user);
    session(['active_company_id' => $company->id]);
    
    $canCreate = $user->can('create', Product::class);
    expect($canCreate)->toBeFalse();
});

test('an owner can update products', function () {
    $company = Company::create(['name' => 'Company A']);
    $user = User::factory()->create();
    $ownerRole = Role::where('key', 'owner')->first();
    $company->users()->attach($user, ['role_id' => $ownerRole->id]);

    $this->actingAs($user);
    session(['active_company_id' => $company->id]);
    
    $product = Product::factory()->create();
    $canUpdate = $user->can('update', $product);
    expect($canUpdate)->toBeTrue();
});
