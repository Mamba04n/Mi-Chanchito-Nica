<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Module;
use Illuminate\Support\Collection;

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
    }

    /**
     * Deactivate a module for a company.
     */
    public function deactivateModule(Company $company, string $moduleKey): void
    {
        $module = Module::where('key', $moduleKey)->firstOrFail();

        $company->modules()->updateExistingPivot($module->id, [
            'disabled_at' => now(),
        ]);
    }

    /**
     * Check if a module is active for a company.
     */
    public function isActive(Company $company, string $moduleKey): bool
    {
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
                throw new \Exception("Cannot activate module {$module->key}. Missing dependency: {$dependency}");
            }
        }
    }
}
