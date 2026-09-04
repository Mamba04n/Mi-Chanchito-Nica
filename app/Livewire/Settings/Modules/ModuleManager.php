<?php

namespace App\Livewire\Settings\Modules;

use Livewire\Component;
use App\Models\Module;
use App\Context\CompanyContext;
use App\Services\ModuleManager as ModuleService;

class ModuleManager extends Component
{
    public $availableModules;
    public $activeModuleKeys = [];

    public function mount(CompanyContext $companyContext)
    {
        $this->availableModules = Module::where('active', true)->get();
        $this->activeModuleKeys = $companyContext->currentCompany()
            ->modules()
            ->whereNull('company_modules.disabled_at')
            ->pluck('key')
            ->toArray();
    }

    public function toggleModule($moduleKey, ModuleService $moduleService, CompanyContext $companyContext)
    {
        $company = $companyContext->currentCompany();

        try {
            if (in_array($moduleKey, $this->activeModuleKeys)) {
                $moduleService->deactivateModule($company, $moduleKey);
                $this->activeModuleKeys = array_diff($this->activeModuleKeys, [$moduleKey]);
                session()->flash('message', 'Módulo desactivado.');
            } else {
                $moduleService->activateModule($company, $moduleKey);
                $this->activeModuleKeys[] = $moduleKey;
                session()->flash('message', 'Módulo activado.');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('settings.modules.index');
    }
}
