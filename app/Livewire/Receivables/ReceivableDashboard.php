<?php

namespace App\Livewire\Receivables;

use App\Services\Receivables\ReceivablesDashboardService;
use Livewire\Component;

class ReceivableDashboard extends Component
{
    public function render(ReceivablesDashboardService $service)
    {
        return view('livewire.receivables.receivable-dashboard', [
            'indicators' => $service->getIndicators(),
        ])->layout('layouts.app');
    }
}
