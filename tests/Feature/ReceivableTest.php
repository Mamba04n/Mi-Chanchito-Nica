<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\AccountReceivable;
use App\Models\FinancialAccount;
use App\Enums\FinancialAccountType;
use App\Enums\ReceivableStatus;
use App\Enums\InvoiceStatus;
use App\Enums\SaleType;
use App\Actions\Receivables\RegisterReceivablePayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class ReceivableTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Customer $customer;
    protected Invoice $invoice;
    protected AccountReceivable $receivable;
    protected FinancialAccount $account;

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
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'treasury');

        $this->account = FinancialAccount::create([
            'company_id' => $this->company->id,
            'name' => 'Caja',
            'type' => FinancialAccountType::CASH,
            'current_balance' => 0
        ]);

        $this->customer = Customer::create(['company_id' => $this->company->id, 'name' => 'C1']);

        $this->invoice = Invoice::create([
            'company_id' => $this->company->id,
            'customer_id' => $this->customer->id,
            'number' => 'FAC-001',
            'issue_date' => now()->toDateString(),
            'sale_type' => SaleType::CREDIT,
            'status' => InvoiceStatus::CONFIRMED,
            'subtotal' => 1000,
            'total' => 1000,
            'paid_amount' => 0,
            'balance' => 1000,
            'created_by' => $this->user->id,
        ]);

        $this->receivable = AccountReceivable::create([
            'company_id' => $this->company->id,
            'customer_id' => $this->customer->id,
            'invoice_id' => $this->invoice->id,
            'original_amount' => 1000,
            'paid_amount' => 0,
            'balance' => 1000,
            'issued_at' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => ReceivableStatus::PENDING,
        ]);
    }

    public function test_can_register_partial_payment()
    {
        app(RegisterReceivablePayment::class)->execute($this->receivable, 400, now()->toDateString(), null, null, $this->account->id);

        $this->receivable->refresh();
        $this->invoice->refresh();
        $this->account->refresh();

        $this->assertEquals(400, $this->receivable->paid_amount);
        $this->assertEquals(600, $this->receivable->balance);
        $this->assertEquals(ReceivableStatus::PARTIAL, $this->receivable->status);

        $this->assertEquals(400, $this->invoice->paid_amount);
        $this->assertEquals(600, $this->invoice->balance);
        $this->assertEquals(InvoiceStatus::PARTIALLY_PAID, $this->invoice->status);

        $this->assertEquals(400, $this->account->current_balance);
    }

    public function test_can_register_full_payment()
    {
        app(RegisterReceivablePayment::class)->execute($this->receivable, 1000, now()->toDateString(), null, null, $this->account->id);

        $this->receivable->refresh();
        $this->invoice->refresh();

        $this->assertEquals(1000, $this->receivable->paid_amount);
        $this->assertEquals(0, $this->receivable->balance);
        $this->assertEquals(ReceivableStatus::PAID, $this->receivable->status);

        $this->assertEquals(InvoiceStatus::PAID, $this->invoice->status);
    }

    public function test_cannot_pay_more_than_balance()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El abono no puede superar el saldo actual");

        app(RegisterReceivablePayment::class)->execute($this->receivable, 1500, now()->toDateString(), null, null, $this->account->id);
    }

    public function test_cannot_pay_zero_or_negative()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El monto del abono debe ser mayor a cero");

        app(RegisterReceivablePayment::class)->execute($this->receivable, 0, now()->toDateString(), null, null, $this->account->id);
    }
}
