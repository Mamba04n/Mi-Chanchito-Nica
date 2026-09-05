<?php

namespace App\Actions\Treasury;

use App\Models\FinancialAccount;
use App\Enums\FinancialAccountType;
use App\Enums\FinancialMovementType;
use App\Context\CompanyContext;
use App\Services\Treasury\TreasuryService;
use Illuminate\Support\Facades\DB;

class CreateFinancialAccount
{
    protected TreasuryService $treasuryService;

    public function __construct(TreasuryService $treasuryService)
    {
        $this->treasuryService = $treasuryService;
    }

    public function execute(array $data): FinancialAccount
    {
        return DB::transaction(function () use ($data) {
            $companyId = app(CompanyContext::class)->getCompanyId();
            
            $account = FinancialAccount::create([
                'company_id' => $companyId,
                'name' => $data['name'],
                'type' => $data['type'] ?? FinancialAccountType::CASH,
                'currency' => $data['currency'] ?? 'NIO',
                'description' => $data['description'] ?? null,
                'active' => $data['active'] ?? true,
                'is_default' => $data['is_default'] ?? false,
                'opening_balance' => 0,
                'current_balance' => 0,
            ]);

            if (isset($data['opening_balance']) && $data['opening_balance'] > 0) {
                $this->treasuryService->recordMovement(
                    $companyId,
                    $account->id,
                    FinancialMovementType::OPENING,
                    $data['opening_balance'],
                    'Saldo inicial',
                    now()->toDateTimeString()
                );
            }
            $account->refresh();

            return $account;
        });
    }
}
