<?php

namespace App\Livewire\Settings\Modules;

use Livewire\Component;
use App\Models\Module;
use App\Context\CompanyContext;
use App\Services\ModuleManager as ModuleService;
use Illuminate\Support\Facades\DB;

class ModuleManager extends Component
{
    public $availableModules = [];
    public $activeModuleKeys = [];
    public $selectedPack = 'personalizado';

    public $packs = [
        'esencial' => [
            'name' => 'Pack Esencial',
            'desc' => 'Para negocios que quieren comenzar a organizar clientes y ventas sin complejidad de inventario.',
            'level' => 'Básico',
            'modules' => ['customers', 'catalog', 'billing', 'receivables']
        ],
        'ventas' => [
            'name' => 'Ventas y Cobranza',
            'desc' => 'Ideal para negocios donde el principal problema es vender, facturar, cobrar y controlar la caja.',
            'level' => 'Intermedio',
            'modules' => ['customers', 'catalog', 'billing', 'receivables', 'treasury']
        ],
        'comercio' => [
            'name' => 'Comercio / Inventario',
            'desc' => 'Perfecto para tiendas y distribuidoras que necesitan controlar existencias físicas y reposición.',
            'level' => 'Avanzado',
            'modules' => ['customers', 'catalog', 'inventory', 'suppliers', 'purchases', 'payables', 'billing', 'receivables']
        ],
        'financiero' => [
            'name' => 'Gestión Financiera',
            'desc' => 'Enfocado en empresas que necesitan controlar ingresos, egresos, obligaciones y flujo de efectivo.',
            'level' => 'Avanzado',
            'modules' => ['customers', 'suppliers', 'catalog', 'billing', 'receivables', 'purchases', 'payables', 'treasury', 'reports']
        ],
        'completo' => [
            'name' => 'Negocio Completo',
            'desc' => 'Toda la suite de gestión para operar tu PyME de inicio a fin.',
            'level' => 'Completo',
            'modules' => ['customers', 'suppliers', 'catalog', 'billing', 'inventory', 'purchases', 'receivables', 'payables', 'treasury', 'reports']
        ]
    ];

    public function mount(CompanyContext $companyContext)
    {
        $this->availableModules = Module::where('active', true)->get()->toArray();
        $this->activeModuleKeys = $companyContext->getCompany()
            ->modules()
            ->whereNull('company_modules.disabled_at')
            ->pluck('key')
            ->toArray();
            
        $this->detectCurrentPack();
    }
    
    public function detectCurrentPack()
    {
        $this->selectedPack = 'personalizado';
        foreach ($this->packs as $key => $pack) {
            $sortedPack = $pack['modules'];
            $sortedActive = $this->activeModuleKeys;
            sort($sortedPack);
            sort($sortedActive);
            if ($sortedPack === $sortedActive) {
                $this->selectedPack = $key;
                break;
            }
        }
    }

    public function selectPack($packKey)
    {
        $this->selectedPack = $packKey;
        if ($packKey !== 'personalizado') {
            $this->activeModuleKeys = $this->packs[$packKey]['modules'];
        }
    }
    
    public function toggleModuleSelection($moduleKey)
    {
        $this->selectedPack = 'personalizado';
        if (in_array($moduleKey, $this->activeModuleKeys)) {
            $this->activeModuleKeys = array_diff($this->activeModuleKeys, [$moduleKey]);
        } else {
            $this->activeModuleKeys[] = $moduleKey;
        }
    }

    public function saveConfiguration(ModuleService $moduleService, CompanyContext $companyContext)
    {
        $company = $companyContext->getCompany();
        
        $currentKeys = $company->modules()
            ->whereNull('company_modules.disabled_at')
            ->pluck('key')
            ->toArray();
            
        $toActivate = array_diff($this->activeModuleKeys, $currentKeys);
        $toDeactivate = array_diff($currentKeys, $this->activeModuleKeys);

        DB::beginTransaction();
        try {
            // First activate new ones (in case they satisfy dependencies)
            foreach ($toActivate as $key) {
                $moduleService->activateModule($company, $key);
            }
            
            // Then deactivate removed ones
            foreach ($toDeactivate as $key) {
                $moduleService->deactivateModule($company, $key);
            }
            
            DB::commit();
            session()->flash('message', 'Configuración de módulos actualizada correctamente.');
            $this->redirectRoute('dashboard', navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
            // Restore visual state
            $this->activeModuleKeys = $currentKeys;
            $this->detectCurrentPack();
        }
    }

    public function render()
    {
        return view('settings.modules.index')->layout('layouts.app');
    }
}
