<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasure;
use App\Models\Warehouse;
use App\Models\Purchase;
use App\Enums\PurchaseStatus;
use App\Enums\PurchaseType;
use App\Enums\PayableStatus;
use App\Actions\Purchases\CreatePurchaseDraft;
use App\Actions\Purchases\ConfirmPurchase;
use App\Actions\Purchases\CancelPurchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchasesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Supplier $supplier;
    protected Product $product;
    protected Product $service;
    protected Warehouse $warehouse;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->user->companies()->attach($this->company->id, ['role_id' => \App\Models\Role::firstOrCreate(['key' => 'owner', 'name' => 'Propietario'])->id, 'status' => 'active']);
        
        $this->actingAs($this->user);
        app(\App\Context\CompanyContext::class)->setCompany($this->company);

        $this->artisan('db:seed', ['--class' => 'ModuleSeeder']);
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'catalog');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'suppliers');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'purchases');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'inventory');

        $this->supplier = Supplier::create(['company_id' => $this->company->id, 'name' => 'Proveedor A']);
        
        $cat = ProductCategory::create(['company_id' => $this->company->id, 'name' => 'Cat']);
        $uom = UnitOfMeasure::create(['company_id' => $this->company->id, 'name' => 'Un', 'abbreviation' => 'u']);

        $this->product = Product::create([
            'company_id' => $this->company->id, 'sku' => 'P1', 'name' => 'Prod',
            'category_id' => $cat->id, 'unit_id' => $uom->id, 'type' => 'product', 'track_inventory' => true
        ]);

        $this->service = Product::create([
            'company_id' => $this->company->id, 'sku' => 'S1', 'name' => 'Serv',
            'category_id' => $cat->id, 'unit_id' => $uom->id, 'type' => 'service', 'track_inventory' => false
        ]);

        $this->warehouse = Warehouse::create([
            'company_id' => $this->company->id, 'code' => 'W1', 'name' => 'Bodega'
        ]);
    }

    public function test_can_create_purchase_draft_and_calculate_totals()
    {
        $action = app(CreatePurchaseDraft::class);

        $purchase = $action->execute([
            'supplier_id' => $this->supplier->id,
            'purchase_date' => now()->toDateString(),
            'purchase_type' => PurchaseType::CREDIT,
        ], [
            [
                'product_id' => $this->product->id,
                'description' => 'Prod',
                'quantity' => 2,
                'unit_cost' => 50,
            ],
            [
                'description' => 'Gasto de envio',
                'quantity' => 1,
                'unit_cost' => 15,
            ]
        ]);

        $this->assertEquals(PurchaseStatus::DRAFT, $purchase->status);
        $this->assertEquals(115, $purchase->subtotal);
        $this->assertEquals(115, $purchase->total);
        $this->assertNull($purchase->number);
    }

    public function test_can_confirm_purchase_product_increases_stock()
    {
        $purchase = Purchase::create([
            'company_id' => $this->company->id,
            'supplier_id' => $this->supplier->id,
            'purchase_date' => now()->toDateString(),
            'purchase_type' => PurchaseType::CREDIT,
            'status' => PurchaseStatus::DRAFT,
            'warehouse_id' => $this->warehouse->id,
            'created_by' => $this->user->id,
        ]);

        $purchase->items()->create([
            'product_id' => $this->product->id,
            'description' => 'Prod',
            'quantity' => 10,
            'unit_cost' => 10,
            'subtotal' => 100,
            'total' => 100,
        ]);

        app(ConfirmPurchase::class)->execute($purchase);

        $purchase->refresh();
        $this->assertEquals(PurchaseStatus::CONFIRMED, $purchase->status);
        $this->assertNotNull($purchase->number);
        $this->assertStringStartsWith('COM-', $purchase->number);

        // Check stock
        $stock = \App\Models\InventoryStock::where('warehouse_id', $this->warehouse->id)
            ->where('product_id', $this->product->id)->first();
        
        $this->assertEquals(10, $stock->quantity);

        // Check CxP
        $this->assertNotNull($purchase->payable);
        $this->assertEquals(100, $purchase->payable->original_amount);
        $this->assertEquals(PayableStatus::PENDING, $purchase->payable->status);
    }

    public function test_cash_purchase_does_not_create_payable()
    {
        $purchase = Purchase::create([
            'company_id' => $this->company->id,
            'supplier_id' => $this->supplier->id,
            'purchase_date' => now()->toDateString(),
            'purchase_type' => PurchaseType::CASH,
            'status' => PurchaseStatus::DRAFT,
            'warehouse_id' => $this->warehouse->id,
            'created_by' => $this->user->id,
        ]);

        $purchase->items()->create([
            'product_id' => $this->product->id,
            'description' => 'Prod',
            'quantity' => 10,
            'unit_cost' => 10,
            'subtotal' => 100,
            'total' => 100,
        ]);

        app(ConfirmPurchase::class)->execute($purchase);
        $purchase->refresh();

        $this->assertEquals(PurchaseStatus::PAID, $purchase->status);
        $this->assertEquals(100, $purchase->paid_amount);
        $this->assertEquals(0, $purchase->balance);
        $this->assertNull($purchase->payable);
    }

    public function test_can_confirm_purchase_service_without_inventory()
    {
        $purchase = Purchase::create([
            'company_id' => $this->company->id,
            'supplier_id' => $this->supplier->id,
            'purchase_date' => now()->toDateString(),
            'purchase_type' => PurchaseType::CREDIT,
            'status' => PurchaseStatus::DRAFT,
            'created_by' => $this->user->id,
        ]);

        $purchase->items()->create([
            'product_id' => $this->service->id,
            'description' => 'Serv',
            'quantity' => 1,
            'unit_cost' => 500,
            'subtotal' => 500,
            'total' => 500,
        ]);

        app(ConfirmPurchase::class)->execute($purchase);
        $purchase->refresh();

        $this->assertEquals(PurchaseStatus::CONFIRMED, $purchase->status);
        
        // No stock was created
        $stock = \App\Models\InventoryStock::where('product_id', $this->service->id)->first();
        $this->assertNull($stock);
    }

    public function test_cancel_purchase_reverses_inventory_and_payable()
    {
        $purchase = Purchase::create([
            'company_id' => $this->company->id,
            'supplier_id' => $this->supplier->id,
            'purchase_date' => now()->toDateString(),
            'purchase_type' => PurchaseType::CREDIT,
            'status' => PurchaseStatus::DRAFT,
            'warehouse_id' => $this->warehouse->id,
            'created_by' => $this->user->id,
        ]);

        $purchase->items()->create([
            'product_id' => $this->product->id,
            'description' => 'Prod',
            'quantity' => 5,
            'unit_cost' => 10,
            'subtotal' => 50,
            'total' => 50,
        ]);

        app(ConfirmPurchase::class)->execute($purchase);
        
        $stock = \App\Models\InventoryStock::where('product_id', $this->product->id)->first();
        $this->assertEquals(5, $stock->quantity);

        app(CancelPurchase::class)->execute($purchase);
        
        $purchase->refresh();
        $this->assertEquals(PurchaseStatus::CANCELLED, $purchase->status);
        $this->assertEquals(PayableStatus::CANCELLED, $purchase->payable->status);

        $stock->refresh();
        $this->assertEquals(0, $stock->quantity); // reversed
    }
}
