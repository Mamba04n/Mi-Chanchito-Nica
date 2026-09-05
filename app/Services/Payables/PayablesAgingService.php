<?php

namespace App\Services\Payables;

use App\Models\AccountPayable;
use App\Context\CompanyContext;
use Carbon\Carbon;

class PayablesAgingService
{
    public function getAging(int $companyId): array
    {
        $payables = AccountPayable::where('company_id', $companyId)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->where('balance', '>', 0)
            ->get();

        $aging = [
            'current' => ['count' => 0, 'amount' => 0],
            '1_30'    => ['count' => 0, 'amount' => 0],
            '31_60'   => ['count' => 0, 'amount' => 0],
            '61_90'   => ['count' => 0, 'amount' => 0],
            'over_90' => ['count' => 0, 'amount' => 0],
            'total'   => ['count' => 0, 'amount' => 0],
        ];

        $today = Carbon::today();

        foreach ($payables as $payable) {
            $dueDate = Carbon::parse($payable->due_date);
            $balance = (float) $payable->balance;

            $aging['total']['count']++;
            $aging['total']['amount'] += $balance;

            if ($dueDate->gte($today)) {
                $aging['current']['count']++;
                $aging['current']['amount'] += $balance;
            } else {
                $daysOverdue = $dueDate->diffInDays($today);

                if ($daysOverdue <= 30) {
                    $aging['1_30']['count']++;
                    $aging['1_30']['amount'] += $balance;
                } elseif ($daysOverdue <= 60) {
                    $aging['31_60']['count']++;
                    $aging['31_60']['amount'] += $balance;
                } elseif ($daysOverdue <= 90) {
                    $aging['61_90']['count']++;
                    $aging['61_90']['amount'] += $balance;
                } else {
                    $aging['over_90']['count']++;
                    $aging['over_90']['amount'] += $balance;
                }
            }
        }

        return $aging;
    }
}
