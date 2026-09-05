<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use App\Enums\SaleType;
use App\Actions\Billing\CreateInvoiceDraft;
use App\Actions\Billing\ConfirmInvoice;
use App\Actions\Billing\CancelInvoice;
use App\Services\Inventory\InventoryService;
use Illuminate\Support\Facades\DB;
use Exception;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;

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
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'customers');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'billing');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'inventory');
    }

    public function test_can_create_invoice_draft_and_calculate_totals()
    {
        $customer = Customer::factory()->create(['company_id' => $this->company->id]);
        
        $data = [
            'customer_id' => $customer->id,
            'issue_date' => now()->format('Y-m-d'),
            'sale_type' => 'credit',
            'items' => [
                [
                    'description' => 'Servicio A',
                    'quantity' => 2,
                    'unit_price' => 100,
                ],
                [
                    'description' => 'Servicio B',
                    'quantity' => 1,
                    'unit_price' => 50,
                    'discount' => 10,
                ]
            ]
        ];

        $action = app(CreateInvoiceDraft::class);
        $invoice = $action->execute($data);

        $this->assertEquals(InvoiceStatus::DRAFT, $invoice->status);
        $this->assertEquals(250, $invoice->subtotal);
        $this->assertEquals(10, $invoice->discount_total);
        $this->assertEquals(240, $invoice->total);
        $this->assertEquals(240, $invoice->balance);
        $this->assertNull($invoice->number); // En draft no hay número todavía
    }

    public function test_can_confirm_invoice_and_deduct_inventory()
    {
        $customer = Customer::factory()->create(['company_id' => $this->company->id]);
        $warehouse = Warehouse::factory()->create(['company_id' => $this->company->id]);
        
        $product = Product::factory()->create([
            'company_id' => $this->company->id,
            'type' => 'product',
            'track_inventory' => true
        ]);

        // Add initial stock
        $invService = app(InventoryService::class);
        $invService->setOpeningStock($warehouse, $product, 10);

        $invoice = Invoice::factory()->create([
            'company_id' => $this->company->id,
            'customer_id' => $customer->id,
            'status' => InvoiceStatus::DRAFT,
            'sale_type' => SaleType::CREDIT,
            'total' => 200,
            'balance' => 200,
            'number' => null
        ]);

        $invoice->items()->create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'description' => 'Producto Prueba',
            'quantity' => 3,
            'unit_price' => 50,
            'subtotal' => 150,
            'total' => 150,
        ]);

        $action = app(ConfirmInvoice::class);
        $action->execute($invoice);

        $invoice->refresh();

        $this->assertEquals(InvoiceStatus::CONFIRMED, $invoice->status);
        $this->assertNotNull($invoice->number);
        
        // Verifica CxC creada
        $this->assertNotNull($invoice->receivable);
        $this->assertEquals(150, $invoice->receivable->balance); // Total recalculado es 3*50 = 150

        // Verifica Inventario reducido
        $stock = \App\Models\InventoryStock::where('product_id', $product->id)->first();
        $this->assertEquals(7, $stock->quantity);
    }

    public function test_cannot_confirm_if_stock_insufficient()
    {
        $customer = Customer::factory()->create(['company_id' => $this->company->id]);
        $warehouse = Warehouse::factory()->create(['company_id' => $this->company->id]);
        
        $product = Product::factory()->create([
            'company_id' => $this->company->id,
            'type' => 'product',
            'track_inventory' => true
        ]);

        $invService = app(InventoryService::class);
        $invService->setOpeningStock($warehouse, $product, 2);

        $invoice = Invoice::factory()->create([
            'company_id' => $this->company->id,
            'status' => InvoiceStatus::DRAFT,
        ]);

        $invoice->items()->create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'description' => 'Prod',
            'quantity' => 5, // exceeds 2
            'unit_price' => 10,
            'subtotal' => 50,
            'total' => 50,
        ]);

        $action = app(ConfirmInvoice::class);

        $this->expectException(Exception::class);
        $action->execute($invoice);
    }

    public function test_cash_sale_does_not_create_receivable()
    {
        $invoice = Invoice::factory()->create([
            'company_id' => $this->company->id,
            'status' => InvoiceStatus::DRAFT,
            'sale_type' => SaleType::CASH,
            'number' => null
        ]);

        $invoice->items()->create([
            'description' => 'Servicio',
            'quantity' => 1,
            'unit_price' => 100,
            'subtotal' => 100,
            'total' => 100,
        ]);

        $action = app(ConfirmInvoice::class);
        $action->execute($invoice);

        $invoice->refresh();

        $this->assertEquals(InvoiceStatus::PAID, $invoice->status);
        $this->assertEquals(0, $invoice->balance);
        $this->assertEquals(100, $invoice->paid_amount);
        $this->assertNull($invoice->receivable);
    }

    public function test_cancel_invoice_reverses_inventory_and_receivable()
    {
        $customer = Customer::factory()->create(['company_id' => $this->company->id]);
        $warehouse = Warehouse::factory()->create(['company_id' => $this->company->id]);
        $product = Product::factory()->create([
            'company_id' => $this->company->id,
            'type' => 'product',
            'track_inventory' => true
        ]);

        app(InventoryService::class)->setOpeningStock($warehouse, $product, 10);

        $invoice = Invoice::factory()->create([
            'company_id' => $this->company->id,
            'status' => InvoiceStatus::DRAFT,
            'sale_type' => SaleType::CREDIT,
        ]);

        $invoice->items()->create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'description' => 'Prod',
            'quantity' => 3,
            'unit_price' => 10,
            'subtotal' => 30,
            'total' => 30,
        ]);

        app(ConfirmInvoice::class)->execute($invoice);
        $invoice->refresh();
        $this->assertEquals(7, \App\Models\InventoryStock::where('product_id', $product->id)->first()->quantity);

        app(CancelInvoice::class)->execute($invoice);

        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::CANCELLED, $invoice->status);
        
        $receivable = $invoice->receivable;
        $this->assertEquals(\App\Enums\ReceivableStatus::CANCELLED, $receivable->status);
        
        // Stock reverted to 10
        $this->assertEquals(10, \App\Models\InventoryStock::where('product_id', $product->id)->first()->quantity);
    }
}
