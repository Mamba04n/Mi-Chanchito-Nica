<?php

namespace App\Actions\Treasury;

use App\Models\FinancialMovement;
use App\Enums\FinancialMovementType;
use App\Context\CompanyContext;
use App\Services\Treasury\TreasuryService;
use Illuminate\Support\Facades\DB;

class RegisterIncome
{
    protected TreasuryService $treasuryService;

    public function __construct(TreasuryService $treasuryService)
    {
        $this->treasuryService = $treasuryService;
    }

    public function execute(array $data): FinancialMovement
    {
        return DB::transaction(function () use ($data) {
            $companyId = app(CompanyContext::class)->getCompanyId();

            return $this->treasuryService->recordMovement(
                $companyId,
                $data['financial_account_id'],
                FinancialMovementType::INCOME,
                $data['amount'],
                $data['description'],
                $data['occurred_at'] ?? now()->toDateTimeString(),
                null, // reference
                $data['notes'] ?? null
            );
        });
    }
}
