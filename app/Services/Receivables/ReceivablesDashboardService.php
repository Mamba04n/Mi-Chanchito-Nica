<?php

namespace App\Services\Receivables;

use App\Models\AccountReceivable;
use App\Enums\ReceivableStatus;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReceivablesDashboardService
{
    protected ReceivablesAgingService $agingService;

    public function __construct(ReceivablesAgingService $agingService)
    {
        $this->agingService = $agingService;
    }

    public function getIndicators(): array
    {
        $companyId = app(CompanyContext::class)->getCompanyId();
        $today = Carbon::today();

        $activeReceivables = AccountReceivable::where('company_id', $companyId)
            ->whereIn('status', [ReceivableStatus::PENDING, ReceivableStatus::PARTIAL, ReceivableStatus::OVERDUE])
            ->where('balance', '>', 0)
            ->get();

        $totalReceivable = $activeReceivables->sum('balance');
        $totalOverdue = $activeReceivables->filter(function ($rec) use ($today) {
            return $rec->due_date && $rec->due_date < $today;
        })->sum('balance');

        $overduePercentage = $totalReceivable > 0 ? ($totalOverdue / $totalReceivable) * 100 : 0;

        $clientsWithDebt = $activeReceivables->pluck('customer_id')->unique()->count();

        // Overdue status update for consistency
        foreach ($activeReceivables as $rec) {
            if ($rec->due_date && $rec->due_date < $today && $rec->status !== ReceivableStatus::OVERDUE) {
                // Not saving here to keep service deterministic/read-only if possible, 
                // but usually a cron updates this. For now we just return the calculated totals.
            }
        }

        $topDebtors = AccountReceivable::where('company_id', $companyId)
            ->whereIn('status', [ReceivableStatus::PENDING, ReceivableStatus::PARTIAL, ReceivableStatus::OVERDUE])
            ->select('customer_id', DB::raw('SUM(balance) as total_debt'))
            ->groupBy('customer_id')
            ->orderBy('total_debt', 'desc')
            ->limit(5)
            ->with('customer:id,name')
            ->get();

        return [
            'total_receivable' => $totalReceivable,
            'total_overdue' => $totalOverdue,
            'overdue_percentage' => $overduePercentage,
            'clients_with_debt' => $clientsWithDebt,
            'top_debtors' => $topDebtors,
            'aging' => $this->agingService->getAging(),
        ];
    }
}
