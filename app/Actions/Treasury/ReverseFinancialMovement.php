<?php

namespace App\Actions\Treasury;

use App\Models\FinancialMovement;
use App\Enums\FinancialMovementType;
use App\Context\CompanyContext;
use App\Services\Treasury\TreasuryService;
use Illuminate\Support\Facades\DB;
use Exception;

class ReverseFinancialMovement
{
    protected TreasuryService $treasuryService;

    public function __construct(TreasuryService $treasuryService)
    {
        $this->treasuryService = $treasuryService;
    }

    public function execute(FinancialMovement $movement, string $reason): FinancialMovement
    {
        return DB::transaction(function () use ($movement, $reason) {
            $companyId = app(CompanyContext::class)->getCompanyId();

            if ($movement->company_id !== $companyId) {
                throw new Exception("El movimiento no pertenece a la empresa activa.");
            }

            // Determine reverse type
            $isAddition = in_array($movement->type, [
                FinancialMovementType::OPENING,
                FinancialMovementType::INCOME,
                FinancialMovementType::TRANSFER_IN,
                FinancialMovementType::ADJUSTMENT_IN,
                FinancialMovementType::RECEIVABLE_PAYMENT
            ]);

            $reverseType = $isAddition ? FinancialMovementType::ADJUSTMENT_OUT : FinancialMovementType::ADJUSTMENT_IN;

            return $this->treasuryService->recordMovement(
                $companyId,
                $movement->financial_account_id,
                $reverseType,
                $movement->amount,
                "Reversión de mov. #{$movement->id}: {$reason}",
                now()->toDateTimeString(),
                $movement // morphTo reference points to the original movement
            );
        });
    }
}
