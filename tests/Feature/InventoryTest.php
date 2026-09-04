<?php

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\InventoryStock;
use App\Services\Inventory\InventoryService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Context\CompanyContext;
use App\Enums\MovementType;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    $this->company = Company::create(['name' => 'Company A']);
    
    $this->user = User::factory()->create();
    $ownerRole = Role::where('key', 'owner')->first();
    $this->company->users()->attach($this->user, ['role_id' => $ownerRole->id]);

    app(CompanyContext::class)->setCompany($this->company);
    $this->actingAs($this->user);

    $this->warehouseMain = Warehouse::factory()->create([
        'company_id' => $this->company->id,
        'code' => 'MAIN',
    ]);

    $this->warehouseBranch = Warehouse::factory()->create([
        'company_id' => $this->company->id,
        'code' => 'BRANCH',
    ]);

    $this->product = Product::factory()->create([
        'company_id' => $this->company->id,
        'type' => 'product',
        'track_inventory' => true,
    ]);

    $this->service = Product::factory()->create([
        'company_id' => $this->company->id,
        'type' => 'service',
        'track_inventory' => false,
    ]);

    $this->inventoryService = app(InventoryService::class);
});

test('opening stock creates movement and updates stock', function () {
    $movement = $this->inventoryService->setOpeningStock($this->warehouseMain, $this->product, 100);

    expect($movement->type)->toBe(MovementType::OPENING);
    expect($movement->quantity)->toEqual(100);

    $stock = InventoryStock::where('warehouse_id', $this->warehouseMain->id)
        ->where('product_id', $this->product->id)->first();
    
    expect($stock->quantity)->toEqual(100);
});

test('entry increases stock and exit decreases stock', function () {
    $this->inventoryService->setOpeningStock($this->warehouseMain, $this->product, 50);

    $this->inventoryService->registerEntry($this->warehouseMain, $this->product, 20);
    $stock = InventoryStock::where('warehouse_id', $this->warehouseMain->id)
        ->where('product_id', $this->product->id)->first();
    
    expect($stock->quantity)->toEqual(70);

    $this->inventoryService->registerExit($this->warehouseMain, $this->product, 15);
    $stock->refresh();
    
    expect($stock->quantity)->toEqual(55);
});

test('cannot register exit if stock is insufficient', function () {
    $this->inventoryService->setOpeningStock($this->warehouseMain, $this->product, 10);

    expect(fn () => $this->inventoryService->registerExit($this->warehouseMain, $this->product, 15))
        ->toThrow(Exception::class, 'No hay suficiente stock disponible');
});

test('cannot move service products', function () {
    expect(fn () => $this->inventoryService->setOpeningStock($this->warehouseMain, $this->service, 10))
        ->toThrow(Exception::class, 'El producto seleccionado no controla inventario.');
});

test('adjustment creates correct movement', function () {
    $this->inventoryService->setOpeningStock($this->warehouseMain, $this->product, 50);
    
    $movement = $this->inventoryService->adjustStock($this->warehouseMain, $this->product, 45, 'Conteo físico');

    expect($movement->type)->toBe(MovementType::ADJUSTMENT_OUT);
    expect($movement->quantity)->toEqual(5);

    $stock = InventoryStock::where('warehouse_id', $this->warehouseMain->id)
        ->where('product_id', $this->product->id)->first();
    expect($stock->quantity)->toEqual(45);
});

test('transfer moves stock between warehouses', function () {
    $this->inventoryService->setOpeningStock($this->warehouseMain, $this->product, 100);
    
    $this->inventoryService->transferStock($this->warehouseMain, $this->warehouseBranch, $this->product, 30);

    $stockMain = InventoryStock::where('warehouse_id', $this->warehouseMain->id)->where('product_id', $this->product->id)->first();
    $stockBranch = InventoryStock::where('warehouse_id', $this->warehouseBranch->id)->where('product_id', $this->product->id)->first();

    expect($stockMain->quantity)->toEqual(70);
    expect($stockBranch->quantity)->toEqual(30);
});

test('cannot transfer to the same warehouse', function () {
    $this->inventoryService->setOpeningStock($this->warehouseMain, $this->product, 100);
    
    expect(fn () => $this->inventoryService->transferStock($this->warehouseMain, $this->warehouseMain, $this->product, 30))
        ->toThrow(Exception::class, 'El almacén de origen y destino deben ser diferentes.');
});

test('cannot access warehouses from another company', function () {
    $companyB = Company::create(['name' => 'Company B']);
    $warehouseB = Warehouse::factory()->create([
        'company_id' => $companyB->id,
        'code' => 'MAIN-B',
    ]);

    expect(fn () => $this->inventoryService->setOpeningStock($warehouseB, $this->product, 100))
        ->toThrow(Exception::class, 'El almacén no pertenece a la empresa activa.');
});
