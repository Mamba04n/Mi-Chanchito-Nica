<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\FinancialAccount;
use App\Enums\FinancialAccountType;
use App\Enums\FinancialMovementType;
use App\Actions\Treasury\CreateFinancialAccount;
use App\Actions\Treasury\RegisterIncome;
use App\Actions\Treasury\RegisterExpense;
use App\Actions\Treasury\TransferFunds;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class TreasuryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected FinancialAccount $cashAccount;
    protected FinancialAccount $bankAccount;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->user->companies()->attach($this->company->id, ['role_id' => \App\Models\Role::firstOrCreate(['key' => 'owner', 'name' => 'Propietario'])->id, 'status' => 'active']);
        
        $this->actingAs($this->user);
        app(\App\Context\CompanyContext::class)->setCompany($this->company);

        $this->artisan('db:seed', ['--class' => 'ModuleSeeder']);
        app(\App\Services\ModuleManager::class)->activateModule($this->company, 'treasury');

        $this->cashAccount = app(CreateFinancialAccount::class)->execute([
            'name' => 'Caja',
            'type' => FinancialAccountType::CASH,
            'opening_balance' => 1000
        ]);

        $this->bankAccount = app(CreateFinancialAccount::class)->execute([
            'name' => 'Banco',
            'type' => FinancialAccountType::BANK,
            'opening_balance' => 5000
        ]);
    }

    public function test_can_create_account_with_opening_balance()
    {
        $this->assertEquals(1000, $this->cashAccount->current_balance);
        $this->assertEquals(1, $this->cashAccount->movements()->count());
        $this->assertEquals(FinancialMovementType::OPENING, $this->cashAccount->movements->first()->type);
    }

    public function test_register_income_increases_balance()
    {
        app(RegisterIncome::class)->execute([
            'financial_account_id' => $this->cashAccount->id,
            'amount' => 500,
            'description' => 'Venta mostrador'
        ]);

        $this->cashAccount->refresh();
        $this->assertEquals(1500, $this->cashAccount->current_balance);
        $this->assertEquals(2, $this->cashAccount->movements()->count());
    }

    public function test_register_expense_decreases_balance()
    {
        app(RegisterExpense::class)->execute([
            'financial_account_id' => $this->cashAccount->id,
            'amount' => 200,
            'description' => 'Compra papelería'
        ]);

        $this->cashAccount->refresh();
        $this->assertEquals(800, $this->cashAccount->current_balance);
        $this->assertEquals(2, $this->cashAccount->movements()->count());
    }

    public function test_expense_fails_if_negative_balance_on_cash_account()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No hay fondos suficientes en la cuenta de Efectivo.");

        app(RegisterExpense::class)->execute([
            'financial_account_id' => $this->cashAccount->id,
            'amount' => 1200,
            'description' => 'Gasto mayor al saldo'
        ]);
    }

    public function test_transfer_funds_subtracts_from_origin_and_adds_to_destination()
    {
        app(TransferFunds::class)->execute([
            'from_account_id' => $this->cashAccount->id,
            'to_account_id' => $this->bankAccount->id,
            'amount' => 300,
            'description' => 'Depósito'
        ]);

        $this->cashAccount->refresh();
        $this->bankAccount->refresh();

        $this->assertEquals(700, $this->cashAccount->current_balance);
        $this->assertEquals(5300, $this->bankAccount->current_balance);
    }

    public function test_transfer_fails_if_same_account()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No se puede transferir fondos a la misma cuenta.");

        app(TransferFunds::class)->execute([
            'from_account_id' => $this->cashAccount->id,
            'to_account_id' => $this->cashAccount->id,
            'amount' => 300
        ]);
    }

    public function test_cannot_register_negative_income_or_expense()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("El monto del movimiento debe ser mayor a cero.");

        app(RegisterIncome::class)->execute([
            'financial_account_id' => $this->cashAccount->id,
            'amount' => -100,
            'description' => 'Negativo'
        ]);
    }
}
