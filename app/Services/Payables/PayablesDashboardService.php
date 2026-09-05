<?php

namespace App\Services\Payables;

use App\Models\AccountPayable;
use App\Context\CompanyContext;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayablesDashboardService
{
    public function getIndicators(int $companyId): array
    {
        $totalBalance = AccountPayable::where('company_id', $companyId)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->sum('balance');

        $overdueBalance = AccountPayable::where('company_id', $companyId)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->where('due_date', '<', Carbon::today())
            ->sum('balance');

        $overduePercentage = $totalBalance > 0 ? ($overdueBalance / $totalBalance) * 100 : 0;

        $topSuppliers = AccountPayable::where('company_id', $companyId)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->select('supplier_id', DB::raw('SUM(balance) as total_balance'))
            ->groupBy('supplier_id')
            ->orderBy('total_balance', 'desc')
            ->with('supplier')
            ->limit(5)
            ->get();

        return [
            'total_balance' => $totalBalance,
            'overdue_balance' => $overdueBalance,
            'overdue_percentage' => $overduePercentage,
            'top_suppliers' => $topSuppliers,
        ];
    }
}
