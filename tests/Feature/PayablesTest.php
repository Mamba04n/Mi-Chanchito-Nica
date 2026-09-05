<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\AccountPayable;
use App\Models\FinancialAccount;
use App\Enums\FinancialAccountType;
use App\Enums\PurchaseStatus;
use App\Enums\PurchaseType;
use App\Enums\PayableStatus;
use App\Actions\Payables\RegisterPayablePayment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayablesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Supplier $supplier;
    protected Purchase $purchase;
    protected AccountPayable $payable;
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
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'suppliers');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'purchases');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'payables');
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'treasury');

        $this->account = FinancialAccount::create([
            'company_id' => $this->company->id,
            'name' => 'Caja',
            'type' => FinancialAccountType::CASH,
            'current_balance' => 5000
        ]);

        $this->supplier = Supplier::create(['company_id' => $this->company->id, 'name' => 'Prov A']);

        $this->purchase = Purchase::create([
            'company_id' => $this->company->id,
            'supplier_id' => $this->supplier->id,
            'number' => 'COM-000001',
            'purchase_date' => now()->toDateString(),
            'purchase_type' => PurchaseType::CREDIT,
            'status' => PurchaseStatus::CONFIRMED,
            'subtotal' => 1000,
            'total' => 1000,
            'paid_amount' => 0,
            'balance' => 1000,
            'created_by' => $this->user->id,
        ]);

        $this->payable = AccountPayable::create([
            'company_id' => $this->company->id,
            'supplier_id' => $this->supplier->id,
            'purchase_id' => $this->purchase->id,
            'original_amount' => 1000,
            'paid_amount' => 0,
            'balance' => 1000,
            'issued_at' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => PayableStatus::PENDING,
        ]);
    }

    public function test_can_register_partial_payment()
    {
        app(RegisterPayablePayment::class)->execute($this->payable->id, 400, now()->toDateString(), null, null, $this->account->id);

        $this->payable->refresh();
        $this->purchase->refresh();
        $this->account->refresh();

        $this->assertEquals(400, $this->payable->paid_amount);
        $this->assertEquals(600, $this->payable->balance);
        $this->assertEquals(PayableStatus::PARTIAL, $this->payable->status);

        $this->assertEquals(400, $this->purchase->paid_amount);
        $this->assertEquals(600, $this->purchase->balance);
        $this->assertEquals(PurchaseStatus::PARTIALLY_PAID, $this->purchase->status);

        $this->assertEquals(4600, $this->account->current_balance);
    }

    public function test_can_register_full_payment()
    {
        app(RegisterPayablePayment::class)->execute($this->payable->id, 1000, now()->toDateString(), null, null, $this->account->id);

        $this->payable->refresh();
        $this->purchase->refresh();

        $this->assertEquals(1000, $this->payable->paid_amount);
        $this->assertEquals(0, $this->payable->balance);
        $this->assertEquals(PayableStatus::PAID, $this->payable->status);

        $this->assertEquals(PurchaseStatus::PAID, $this->purchase->status);
    }

    public function test_cannot_pay_more_than_balance()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El abono no puede superar el saldo actual de la CxP.");

        app(RegisterPayablePayment::class)->execute($this->payable->id, 1500, now()->toDateString(), null, null, $this->account->id);
    }

    public function test_cannot_pay_zero_or_negative()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El abono debe ser mayor a cero.");

        app(RegisterPayablePayment::class)->execute($this->payable->id, 0, now()->toDateString(), null, null, $this->account->id);
    }

    public function test_fails_if_insufficient_funds_in_cash_account()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No hay fondos suficientes en la cuenta de Efectivo");

        $this->account->current_balance = 500;
        $this->account->save();

        app(RegisterPayablePayment::class)->execute($this->payable->id, 1000, now()->toDateString(), null, null, $this->account->id);
    }
}
