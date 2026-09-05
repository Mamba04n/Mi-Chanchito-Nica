<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\AccountReceivable;
use App\Enums\InvoiceStatus;
use App\Enums\SaleType;
use App\Enums\PurchaseStatus;
use App\Enums\PurchaseType;
use App\Enums\ReceivableStatus;
use App\Services\Dashboard\BusinessDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class DashboardTest extends TestCase
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
        
        // Active all modules for full test
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'catalog');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'customers');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'suppliers');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'billing');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'receivables');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'purchases');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'payables');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'inventory');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'treasury');
    }

    public function test_dashboard_returns_sales_summary()
    {
        $customer = Customer::create(['company_id' => $this->company->id, 'name' => 'C1']);

        // Valid sale
        Invoice::create([
            'company_id' => $this->company->id,
            'customer_id' => $customer->id,
            'number' => 'FAC-001',
            'issue_date' => now()->toDateString(),
            'sale_type' => SaleType::CASH,
            'status' => InvoiceStatus::PAID,
            'subtotal' => 1000,
            'total' => 1000,
            'paid_amount' => 1000,
            'balance' => 0,
            'created_by' => $this->user->id,
        ]);

        // Invalid sale (draft) should be excluded
        Invoice::create([
            'company_id' => $this->company->id,
            'customer_id' => $customer->id,
            'number' => null,
            'issue_date' => now()->toDateString(),
            'sale_type' => SaleType::CREDIT,
            'status' => InvoiceStatus::DRAFT,
            'subtotal' => 2000,
            'total' => 2000,
            'paid_amount' => 0,
            'balance' => 2000,
            'created_by' => $this->user->id,
        ]);

        $service = app(BusinessDashboardService::class);
        $data = $service->getDashboard($this->company, Carbon::now()->startOfMonth(), Carbon::now()->endOfDay());

        $this->assertEquals(1000, $data['sales']['total_period']);
        $this->assertEquals(1, $data['sales']['count']);
        $this->assertEquals(1000, $data['sales']['cash_sales']);
        $this->assertEquals(0, $data['sales']['credit_sales']);
    }

    public function test_dashboard_returns_unavailable_for_disabled_modules()
    {
        app(\App\Services\ModuleManager::class)->deactivateModule($this->company, 'inventory');

        $service = app(BusinessDashboardService::class);
        $data = $service->getDashboard($this->company);

        $this->assertEquals('unavailable', $data['inventory']);
        $this->assertNotEquals('unavailable', $data['receivables']);
    }
}
