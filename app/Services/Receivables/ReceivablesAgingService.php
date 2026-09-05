<?php

namespace App\Services\Receivables;

use App\Models\AccountReceivable;
use App\Enums\ReceivableStatus;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReceivablesAgingService
{
    /**
     * Calculates the aging of the receivables portfolio for the active company.
     * Buckets: Current, 1-30 days, 31-60 days, 61-90 days, 90+ days past due.
     */
    public function getAging(): array
    {
        $companyId = app(CompanyContext::class)->getCompanyId();
        $today = Carbon::today();

        $receivables = AccountReceivable::where('company_id', $companyId)
            ->whereIn('status', [ReceivableStatus::PENDING, ReceivableStatus::PARTIAL, ReceivableStatus::OVERDUE])
            ->where('balance', '>', 0)
            ->get();

        $aging = [
            'current' => ['count' => 0, 'amount' => 0],
            '1_30' => ['count' => 0, 'amount' => 0],
            '31_60' => ['count' => 0, 'amount' => 0],
            '61_90' => ['count' => 0, 'amount' => 0],
            '90_plus' => ['count' => 0, 'amount' => 0],
            'total' => ['count' => 0, 'amount' => 0],
        ];

        foreach ($receivables as $rec) {
            $balance = (float)$rec->balance;
            
            $aging['total']['count']++;
            $aging['total']['amount'] += $balance;

            if (!$rec->due_date || $rec->due_date >= $today) {
                $aging['current']['count']++;
                $aging['current']['amount'] += $balance;
            } else {
                $daysPastDue = $today->diffInDays($rec->due_date);

                if ($daysPastDue <= 30) {
                    $aging['1_30']['count']++;
                    $aging['1_30']['amount'] += $balance;
                } elseif ($daysPastDue <= 60) {
                    $aging['31_60']['count']++;
                    $aging['31_60']['amount'] += $balance;
                } elseif ($daysPastDue <= 90) {
                    $aging['61_90']['count']++;
                    $aging['61_90']['amount'] += $balance;
                } else {
                    $aging['90_plus']['count']++;
                    $aging['90_plus']['amount'] += $balance;
                }
            }
        }

        return $aging;
    }
}
