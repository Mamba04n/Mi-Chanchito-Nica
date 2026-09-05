<?php

namespace App\Services\Dashboard;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Enums\InvoiceStatus;
use App\Enums\PurchaseStatus;
use App\Enums\SaleType;
use App\Enums\PurchaseType;
use App\Services\ModuleManager;
use App\Services\Receivables\ReceivablesDashboardService;
use App\Services\Payables\PayablesDashboardService;
use App\Services\Treasury\TreasuryDashboardService;
use App\Services\Inventory\InventoryDashboardService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BusinessDashboardService
{
    protected ModuleManager $moduleManager;
    protected ReceivablesDashboardService $receivablesDashboard;
    protected PayablesDashboardService $payablesDashboard;
    protected TreasuryDashboardService $treasuryDashboard;
    protected InventoryDashboardService $inventoryDashboard;
    protected BusinessAlertService $alertService;

    public function __construct(
        ModuleManager $moduleManager,
        ReceivablesDashboardService $receivablesDashboard,
        PayablesDashboardService $payablesDashboard,
        TreasuryDashboardService $treasuryDashboard,
        InventoryDashboardService $inventoryDashboard,
        BusinessAlertService $alertService
    ) {
        $this->moduleManager = $moduleManager;
        $this->receivablesDashboard = $receivablesDashboard;
        $this->payablesDashboard = $payablesDashboard;
        $this->treasuryDashboard = $treasuryDashboard;
        $this->inventoryDashboard = $inventoryDashboard;
        $this->alertService = $alertService;
    }

    public function getDashboard(Company $company, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfDay();
        
        $data = [
            'period' => [
                'from' => $startDate->toDateString(),
                'to' => $endDate->toDateString(),
            ],
            'sales' => $this->getSalesSummary($company->id, $startDate, $endDate),
            'purchases' => $this->getPurchasesSummary($company->id, $startDate, $endDate),
        ];

        // Modules check
        $data['receivables'] = $this->moduleManager->isActive($company, 'receivables') 
            ? $this->receivablesDashboard->getIndicators() 
            : 'unavailable';

        $data['payables'] = $this->moduleManager->isActive($company, 'payables') 
            ? $this->payablesDashboard->getIndicators($company->id) 
            : 'unavailable';

        // Note: TreasuryDashboardService signature is getDashboardMetrics(companyId, days)
        // We will adapt it slightly here by just calling it for the period diff.
        $days = (int) $startDate->diffInDays($endDate);
        if ($days === 0) $days = 1;
        
        $data['treasury'] = $this->moduleManager->isActive($company, 'treasury') 
            ? $this->treasuryDashboard->getDashboardMetrics($company->id, $days) 
            : 'unavailable';

        $data['inventory'] = $this->moduleManager->isActive($company, 'inventory') 
            ? $this->inventoryDashboard->getIndicators($company->id) 
            : 'unavailable';

        // Alerts & Statuses
        $health = $this->alertService->generateAlerts($data);
        $data['alerts'] = $health['alerts'];
        $data['domain_statuses'] = $health['domain_statuses'];

        return $data;
    }

    protected function getSalesSummary(int $companyId, Carbon $start, Carbon $end): array
    {
        // Valid sales are confirmed, partially paid, paid, overdue.
        // Draft and cancelled are excluded.
        $validStatuses = [
            InvoiceStatus::CONFIRMED,
            InvoiceStatus::PARTIALLY_PAID,
            InvoiceStatus::PAID,
            InvoiceStatus::OVERDUE,
        ];

        $query = Invoice::where('company_id', $companyId)
            ->whereIn('status', $validStatuses)
            ->whereBetween('issue_date', [$start->startOfDay()->toDateTimeString(), $end->endOfDay()->toDateTimeString()]);

        $count = $query->count();
        $totalPeriod = (float) $query->sum('total');
        
        // Sums by type
        $cashSales = (float) (clone $query)->where('sale_type', SaleType::CASH)->sum('total');
        $creditSales = (float) (clone $query)->where('sale_type', SaleType::CREDIT)->sum('total');
        $pendingBalance = (float) $query->sum('balance'); // what hasn't been collected from these period sales

        $topCustomers = Invoice::where('company_id', $companyId)
            ->whereIn('status', $validStatuses)
            ->whereBetween('issue_date', [$start->startOfDay()->toDateTimeString(), $end->endOfDay()->toDateTimeString()])
            ->select('customer_id', DB::raw('SUM(total) as volume'))
            ->groupBy('customer_id')
            ->orderBy('volume', 'desc')
            ->limit(5)
            ->with('customer:id,name')
            ->get();
            
        $dailySeries = Invoice::where('company_id', $companyId)
            ->whereIn('status', $validStatuses)
            ->whereBetween('issue_date', [$start->startOfDay()->toDateTimeString(), $end->endOfDay()->toDateTimeString()])
            ->select('issue_date', DB::raw('SUM(total) as daily_total'))
            ->groupBy('issue_date')
            ->orderBy('issue_date')
            ->pluck('daily_total', 'issue_date');

        return [
            'total_period' => $totalPeriod,
            'count' => $count,
            'average_ticket' => $count > 0 ? $totalPeriod / $count : 0,
            'cash_sales' => $cashSales,
            'credit_sales' => $creditSales,
            'pending_balance' => $pendingBalance,
            'top_customers' => $topCustomers,
            'daily_series' => $dailySeries,
        ];
    }

    protected function getPurchasesSummary(int $companyId, Carbon $start, Carbon $end): array
    {
        $validStatuses = [
            PurchaseStatus::CONFIRMED,
            PurchaseStatus::PARTIALLY_PAID,
            PurchaseStatus::PAID,
        ];

        $query = Purchase::where('company_id', $companyId)
            ->whereIn('status', $validStatuses)
            ->whereBetween('purchase_date', [$start->startOfDay()->toDateTimeString(), $end->endOfDay()->toDateTimeString()]);

        $count = $query->count();
        $totalPeriod = (float) $query->sum('total');
        
        $cashPurchases = (float) (clone $query)->where('purchase_type', PurchaseType::CASH)->sum('total');
        $creditPurchases = (float) (clone $query)->where('purchase_type', PurchaseType::CREDIT)->sum('total');
        $pendingBalance = (float) $query->sum('balance');

        $topSuppliers = Purchase::where('company_id', $companyId)
            ->whereIn('status', $validStatuses)
            ->whereBetween('purchase_date', [$start->startOfDay()->toDateTimeString(), $end->endOfDay()->toDateTimeString()])
            ->select('supplier_id', DB::raw('SUM(total) as volume'))
            ->groupBy('supplier_id')
            ->orderBy('volume', 'desc')
            ->limit(5)
            ->with('supplier:id,name')
            ->get();

        $dailySeries = Purchase::where('company_id', $companyId)
            ->whereIn('status', $validStatuses)
            ->whereBetween('purchase_date', [$start->startOfDay()->toDateTimeString(), $end->endOfDay()->toDateTimeString()])
            ->select('purchase_date', DB::raw('SUM(total) as daily_total'))
            ->groupBy('purchase_date')
            ->orderBy('purchase_date')
            ->pluck('daily_total', 'purchase_date');

        return [
            'total_period' => $totalPeriod,
            'count' => $count,
            'cash_purchases' => $cashPurchases,
            'credit_purchases' => $creditPurchases,
            'pending_balance' => $pendingBalance,
            'top_suppliers' => $topSuppliers,
            'daily_series' => $dailySeries,
        ];
    }
}
