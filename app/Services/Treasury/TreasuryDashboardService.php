<?php

namespace App\Services\Treasury;

use App\Models\FinancialAccount;
use App\Models\FinancialMovement;
use App\Enums\FinancialMovementType;
use App\Context\CompanyContext;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TreasuryDashboardService
{
    public function getDashboardMetrics(int $companyId, int $days = 30): array
    {
        $startDate = Carbon::today()->subDays($days)->startOfDay();

        $accounts = FinancialAccount::where('company_id', $companyId)
            ->where('active', true)
            ->get();

        $totalBalance = $accounts->sum('current_balance');
        
        $distribution = $accounts->map(function ($acc) use ($totalBalance) {
            return [
                'id' => $acc->id,
                'name' => $acc->name,
                'balance' => $acc->current_balance,
                'percentage_of_total' => $totalBalance > 0 ? round(($acc->current_balance / $totalBalance) * 100, 2) : 0,
            ];
        });

        $additions = [
            FinancialMovementType::OPENING,
            FinancialMovementType::INCOME,
            FinancialMovementType::ADJUSTMENT_IN,
            FinancialMovementType::RECEIVABLE_PAYMENT
        ];

        $subtractions = [
            FinancialMovementType::EXPENSE,
            FinancialMovementType::ADJUSTMENT_OUT,
            FinancialMovementType::PAYABLE_PAYMENT
        ];

        // Period metrics (excluding transfers to avoid double counting)
        $totalIn = FinancialMovement::where('company_id', $companyId)
            ->whereIn('type', $additions)
            ->where('occurred_at', '>=', $startDate)
            ->sum('amount');

        $totalOut = FinancialMovement::where('company_id', $companyId)
            ->whereIn('type', $subtractions)
            ->where('occurred_at', '>=', $startDate)
            ->sum('amount');

        $netFlow = $totalIn - $totalOut;

        // Daily grouped for chart
        $dailyIn = FinancialMovement::where('company_id', $companyId)
            ->whereIn('type', $additions)
            ->where('occurred_at', '>=', $startDate)
            ->select(DB::raw('DATE(occurred_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $dailyOut = FinancialMovement::where('company_id', $companyId)
            ->whereIn('type', $subtractions)
            ->where('occurred_at', '>=', $startDate)
            ->select(DB::raw('DATE(occurred_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return [
            'total_balance' => $totalBalance,
            'active_accounts_count' => $accounts->count(),
            'period_in' => $totalIn,
            'period_out' => $totalOut,
            'net_flow' => $netFlow,
            'distribution' => $distribution,
            'chart_data' => [
                'income' => $dailyIn,
                'expense' => $dailyOut,
            ]
        ];
    }
}
