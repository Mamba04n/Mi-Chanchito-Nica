<?php

namespace App\Actions\Treasury;

use App\Models\FinancialMovement;
use App\Enums\FinancialMovementType;
use App\Context\CompanyContext;
use App\Services\Treasury\TreasuryService;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferFunds
{
    protected TreasuryService $treasuryService;

    public function __construct(TreasuryService $treasuryService)
    {
        $this->treasuryService = $treasuryService;
    }

    public function execute(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $companyId = app(CompanyContext::class)->getCompanyId();

            if ($data['from_account_id'] === $data['to_account_id']) {
                throw new Exception("No se puede transferir fondos a la misma cuenta.");
            }

            $amount = $data['amount'];
            $description = $data['description'] ?? 'Transferencia de fondos';
            $occurredAt = $data['occurred_at'] ?? now()->toDateTimeString();

            // Withdraw from source
            $transferOut = $this->treasuryService->recordMovement(
                $companyId,
                $data['from_account_id'],
                FinancialMovementType::TRANSFER_OUT,
                $amount,
                $description,
                $occurredAt,
                null,
                "Transferencia hacia cuenta ID: {$data['to_account_id']}"
            );

            // Deposit to destination
            $transferIn = $this->treasuryService->recordMovement(
                $companyId,
                $data['to_account_id'],
                FinancialMovementType::TRANSFER_IN,
                $amount,
                $description,
                $occurredAt,
                null,
                "Transferencia desde cuenta ID: {$data['from_account_id']}"
            );

            // Cross-reference movements using reference_id 
            // Setting reference to each other. We use morph manually here since it's the same table.
            $transferOut->reference_type = FinancialMovement::class;
            $transferOut->reference_id = $transferIn->id;
            $transferOut->save();

            $transferIn->reference_type = FinancialMovement::class;
            $transferIn->reference_id = $transferOut->id;
            $transferIn->save();

            return [
                'out' => $transferOut,
                'in' => $transferIn
            ];
        });
    }
}
