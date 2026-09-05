<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $company = app(\App\Context\CompanyContext::class)->getCompany();
        $activeModulesCount = $company ? $company->modules()->whereNull('company_modules.disabled_at')->count() : 0;

        return view('livewire.dashboard.dashboard', [
            'activeModulesCount' => $activeModulesCount
        ])->layout('layouts.app');
    }
}
