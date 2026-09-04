<?php

namespace App\Livewire\Settings\Company;

use Livewire\Component;
use App\Context\CompanyContext;
use App\Services\AuditLogger;

class EditCompany extends Component
{
    public $name;
    public $country_code;
    public $currency_code;
    public $timezone;

    public function mount(CompanyContext $companyContext)
    {
        $company = $companyContext->currentCompany();
        $this->name = $company->name;
        $this->country_code = $company->country_code;
        $this->currency_code = $company->currency_code;
        $this->timezone = $company->timezone;
    }

    public function save(CompanyContext $companyContext)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:2',
            'currency_code' => 'required|string|max:3',
            'timezone' => 'required|string|max:50',
        ]);

        $company = $companyContext->currentCompany();
        $company->update([
            'name' => $this->name,
            'country_code' => $this->country_code,
            'currency_code' => $this->currency_code,
            'timezone' => $this->timezone,
        ]);

        AuditLogger::log('company.updated', $company->id, \App\Models\Company::class, $company->id);

        session()->flash('message', 'Empresa actualizada correctamente.');
    }

    public function render()
    {
        return view('settings.company.edit');
    }
}
