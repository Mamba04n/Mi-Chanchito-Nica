<?php

namespace App\Policies;

use App\Models\Warehouse;
use App\Models\User;
use App\Context\CompanyContext;

class WarehousePolicy
{
    protected function checkCompany(User $user, $model)
    {
        $currentCompany = app(CompanyContext::class)->getCompany();
        if (!$currentCompany) return false;
        return $model->company_id === $currentCompany->id;
    }

    public function viewAny(User $user): bool
    {
        $currentCompany = app(CompanyContext::class)->getCompany();
        if (!$currentCompany) return false;
        return $user->hasPermission('inventory.view', $currentCompany);
    }

    public function view(User $user, Warehouse $warehouse): bool
    {
        if (!$this->checkCompany($user, $warehouse)) return false;
        return $user->hasPermission('inventory.view', app(CompanyContext::class)->getCompany());
    }

    public function create(User $user): bool
    {
        $currentCompany = app(CompanyContext::class)->getCompany();
        if (!$currentCompany) return false;
        return $user->hasPermission('inventory.warehouses.manage', $currentCompany);
    }

    public function update(User $user, Warehouse $warehouse): bool
    {
        if (!$this->checkCompany($user, $warehouse)) return false;
        return $user->hasPermission('inventory.warehouses.manage', app(CompanyContext::class)->getCompany());
    }

    public function delete(User $user, Warehouse $warehouse): bool
    {
        if (!$this->checkCompany($user, $warehouse)) return false;
        return $user->hasPermission('inventory.warehouses.manage', app(CompanyContext::class)->getCompany());
    }
}
