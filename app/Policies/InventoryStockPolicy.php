<?php

namespace App\Policies;

use App\Models\InventoryStock;
use App\Models\User;
use App\Context\CompanyContext;

class InventoryStockPolicy
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

    public function view(User $user, InventoryStock $inventorystock): bool
    {
        if (!$this->checkCompany($user, $inventorystock)) return false;
        return $user->hasPermission('inventory.view', app(CompanyContext::class)->getCompany());
    }

    public function create(User $user): bool
    {
        $currentCompany = app(CompanyContext::class)->getCompany();
        if (!$currentCompany) return false;
        return $user->hasPermission('inventory.create', $currentCompany);
    }

    public function update(User $user, InventoryStock $inventorystock): bool
    {
        if (!$this->checkCompany($user, $inventorystock)) return false;
        return $user->hasPermission('inventory.create', app(CompanyContext::class)->getCompany());
    }

    public function delete(User $user, InventoryStock $inventorystock): bool
    {
        if (!$this->checkCompany($user, $inventorystock)) return false;
        return $user->hasPermission('inventory.create', app(CompanyContext::class)->getCompany());
    }
}
