<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\AccountReceivable;
use App\Enums\InvoiceStatus;
use App\Enums\SaleType;
use App\Enums\ReceivableStatus;
use App\Services\Dashboard\BusinessDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AlertTest extends TestCase
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
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'receivables');
    }

    public function test_generates_critical_alert_for_overdue_receivables()
    {
        $customer = Customer::create(['company_id' => $this->company->id, 'name' => 'C1']);

        // Overdue Invoice
        $inv = Invoice::create([
            'company_id' => $this->company->id,
            'customer_id' => $customer->id,
            'number' => 'FAC-001',
            'issue_date' => Carbon::now()->subDays(60)->toDateString(),
            'sale_type' => SaleType::CREDIT,
            'status' => InvoiceStatus::CONFIRMED,
            'subtotal' => 1000,
            'total' => 1000,
            'paid_amount' => 0,
            'balance' => 1000,
            'created_by' => $this->user->id,
        ]);

        AccountReceivable::create([
            'company_id' => $this->company->id,
            'customer_id' => $customer->id,
            'invoice_id' => $inv->id,
            'original_amount' => 1000,
            'paid_amount' => 0,
            'balance' => 1000,
            'issued_at' => Carbon::now()->subDays(60)->toDateString(),
            'due_date' => Carbon::now()->subDays(30)->toDateString(),
            'status' => ReceivableStatus::OVERDUE,
        ]);

        $service = app(BusinessDashboardService::class);
        $data = $service->getDashboard($this->company, Carbon::now()->startOfMonth(), Carbon::now()->endOfDay());

        $this->assertContains('high_overdue_receivables', collect($data['alerts'])->pluck('key'));
        $this->assertEquals('critical', $data['domain_statuses']['receivables']);
    }

    public function test_generates_no_alert_for_healthy_receivables()
    {
        $customer = Customer::create(['company_id' => $this->company->id, 'name' => 'C1']);

        // Current Invoice
        $inv = Invoice::create([
            'company_id' => $this->company->id,
            'customer_id' => $customer->id,
            'number' => 'FAC-002',
            'issue_date' => Carbon::now()->subDays(5)->toDateString(),
            'sale_type' => SaleType::CREDIT,
            'status' => InvoiceStatus::CONFIRMED,
            'subtotal' => 1000,
            'total' => 1000,
            'paid_amount' => 0,
            'balance' => 1000,
            'created_by' => $this->user->id,
        ]);

        AccountReceivable::create([
            'company_id' => $this->company->id,
            'customer_id' => $customer->id,
            'invoice_id' => $inv->id,
            'original_amount' => 1000,
            'paid_amount' => 0,
            'balance' => 1000,
            'issued_at' => Carbon::now()->subDays(5)->toDateString(),
            'due_date' => Carbon::now()->addDays(25)->toDateString(),
            'status' => ReceivableStatus::PENDING,
        ]);

        $service = app(BusinessDashboardService::class);
        $data = $service->getDashboard($this->company, Carbon::now()->startOfMonth(), Carbon::now()->endOfDay());

        $this->assertNotContains('high_overdue_receivables', collect($data['alerts'])->pluck('key'));
        $this->assertEquals('healthy', $data['domain_statuses']['receivables']);
    }
}
