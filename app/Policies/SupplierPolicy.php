<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use App\Context\CompanyContext;

class SupplierPolicy
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
        return $user->hasPermission('suppliers.view', $currentCompany);
    }

    public function view(User $user, Supplier $supplier): bool
    {
        if (!$this->checkCompany($user, $supplier)) return false;
        return $user->hasPermission('suppliers.view', app(CompanyContext::class)->getCompany());
    }

    public function create(User $user): bool
    {
        $currentCompany = app(CompanyContext::class)->getCompany();
        if (!$currentCompany) return false;
        return $user->hasPermission('suppliers.create', $currentCompany);
    }

    public function update(User $user, Supplier $supplier): bool
    {
        if (!$this->checkCompany($user, $supplier)) return false;
        return $user->hasPermission('suppliers.update', app(CompanyContext::class)->getCompany());
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        if (!$this->checkCompany($user, $supplier)) return false;
        return $user->hasPermission('suppliers.delete', app(CompanyContext::class)->getCompany());
    }
}
