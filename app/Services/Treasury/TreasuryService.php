<?php

namespace App\Services\Treasury;

use App\Models\FinancialAccount;
use App\Models\FinancialMovement;
use App\Enums\FinancialAccountType;
use App\Enums\FinancialMovementType;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Exception;

class TreasuryService
{
    /**
     * Records a financial movement ensuring strict concurrency and balance calculations.
     * MUST be called inside a DB::transaction.
     */
    public function recordMovement(
        int $companyId,
        int $accountId,
        FinancialMovementType $type,
        float $amount,
        string $description,
        ?string $occurredAt = null,
        ?Model $reference = null,
        ?string $notes = null
    ): FinancialMovement {
        if ($amount <= 0 && $type !== FinancialMovementType::OPENING) {
            throw new Exception("El monto del movimiento debe ser mayor a cero.");
        }

        $account = FinancialAccount::where('id', $accountId)
            ->where('company_id', $companyId)
            ->lockForUpdate()
            ->firstOrFail();

        if (!$account->active) {
            throw new Exception("La cuenta financiera seleccionada se encuentra inactiva.");
        }

        $isAddition = in_array($type, [
            FinancialMovementType::OPENING, // usually positive
            FinancialMovementType::INCOME,
            FinancialMovementType::TRANSFER_IN,
            FinancialMovementType::ADJUSTMENT_IN,
            FinancialMovementType::RECEIVABLE_PAYMENT
        ]);

        $isSubtraction = in_array($type, [
            FinancialMovementType::EXPENSE,
            FinancialMovementType::TRANSFER_OUT,
            FinancialMovementType::ADJUSTMENT_OUT,
            FinancialMovementType::PAYABLE_PAYMENT
        ]);

        $previousBalance = (float) $account->current_balance;

        // If it's an opening balance, amount can be negative technically if starting in debt, 
        // but let's assume it's just added to previous balance (which is 0).
        if ($isAddition) {
            $newBalance = $previousBalance + $amount;
        } elseif ($isSubtraction) {
            $newBalance = $previousBalance - $amount;
            
            // Validate negative balance for Cash accounts (ADR)
            if ($account->type === FinancialAccountType::CASH && $newBalance < 0) {
                throw new Exception("No hay fondos suficientes en la cuenta de Efectivo. Saldo actual: {$previousBalance}.");
            }
        } else {
            throw new Exception("Tipo de movimiento financiero desconocido.");
        }

        $movement = FinancialMovement::create([
            'company_id' => $companyId,
            'financial_account_id' => $account->id,
            'type' => $type,
            'amount' => $amount,
            'currency' => $account->currency,
            'description' => $description,
            'notes' => $notes,
            'occurred_at' => $occurredAt ?? now(),
            'created_by' => auth()->id() ?? 1, // fallback for console/tests
            'previous_balance' => $previousBalance,
            'new_balance' => $newBalance,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->getKey() : null,
        ]);

        $account->current_balance = $newBalance;
        if ($type === FinancialMovementType::OPENING) {
            $account->opening_balance = $amount;
        }
        $account->save();

        \App\Services\AuditLogger::log("treasury.{$type->value}", $companyId, FinancialMovement::class, $movement->id, [
            'amount' => $amount,
            'account' => $account->id
        ]);

        return $movement;
    }
}
