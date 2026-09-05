<?php

namespace App\Livewire\Payables;

use Livewire\Component;
use App\Services\Payables\PayablesDashboardService;
use App\Context\CompanyContext;

class PayableDashboard extends Component
{
    public function render()
    {
        $company = app(CompanyContext::class)->getCompany();
        $indicators = app(PayablesDashboardService::class)->getIndicators($company->id);

        return view('livewire.payables.payable-dashboard', [
            'indicators' => $indicators
        ])->layout('layouts.app');
    }
}
