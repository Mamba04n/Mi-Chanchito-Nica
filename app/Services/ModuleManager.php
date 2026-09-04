<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Module;
use Illuminate\Support\Collection;
use App\Services\AuditLogger;

class ModuleManager
{
    /**
     * Get all available modules.
     */
    public function getAvailableModules(): Collection
    {
        return Module::where('active', true)->get();
    }

    /**
     * Activate a module for a company.
     */
    public function activateModule(Company $company, string $moduleKey, array $settings = []): void
    {
        $module = Module::where('key', $moduleKey)->firstOrFail();

        $this->validateDependencies($company, $module);

        $company->modules()->syncWithoutDetaching([
            $module->id => [
                'enabled_at' => now(),
                'disabled_at' => null,
                'settings' => empty($settings) ? null : json_encode($settings),
            ]
        ]);

        AuditLogger::log('module.enabled', $company->id, Module::class, $module->id, ['key' => $moduleKey]);
    }

    /**
     * Deactivate a module for a company.
     */
    public function deactivateModule(Company $company, string $moduleKey): void
    {
        $module = Module::where('key', $moduleKey)->firstOrFail();

        // Prevent disabling if other active modules depend on it
        $this->validateNoDependents($company, $module);

        $company->modules()->updateExistingPivot($module->id, [
            'disabled_at' => now(),
        ]);

        AuditLogger::log('module.disabled', $company->id, Module::class, $module->id, ['key' => $moduleKey]);
    }

    /**
     * Check if a module is active for a company.
     */
    public function isActive(Company $company, string $moduleKey): bool
    {
        // For core features built as standard (e.g. core, customers, catalog)
        // If they are not in the modules table or are mandatory, we could return true.
        // For now, we check the pivot.
        $module = $company->modules()->where('key', $moduleKey)->first();

        return $module && is_null($module->pivot->disabled_at);
    }

    /**
     * Validate dependencies before activating a module.
     */
    protected function validateDependencies(Company $company, Module $module): void
    {
        if (empty($module->dependencies)) {
            return;
        }

        $activeModules = $company->modules()
            ->whereNull('company_modules.disabled_at')
            ->pluck('key')
            ->toArray();

        foreach ($module->dependencies as $dependency) {
            if (!in_array($dependency, $activeModules)) {
                throw new \Exception("No se puede activar '{$module->name}'. Requiere el módulo: {$dependency}");
            }
        }
    }

    /**
     * Validate that no active modules depend on the one being disabled.
     */
    protected function validateNoDependents(Company $company, Module $moduleToDisable): void
    {
        $activeModules = $company->modules()
            ->whereNull('company_modules.disabled_at')
            ->get();

        foreach ($activeModules as $activeModule) {
            if (!empty($activeModule->dependencies) && in_array($moduleToDisable->key, $activeModule->dependencies)) {
                throw new \Exception("No se puede desactivar '{$moduleToDisable->name}'. El módulo '{$activeModule->name}' depende de él.");
            }
        }
    }
}
