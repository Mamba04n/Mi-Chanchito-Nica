<?php

namespace App\Policies;

use App\Models\UnitOfMeasure;
use App\Models\User;
use App\Context\CompanyContext;

class UnitOfMeasurePolicy
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
        return $user->hasPermission('catalog.view', $currentCompany);
    }

    public function view(User $user, UnitOfMeasure $unitofmeasure): bool
    {
        if (!$this->checkCompany($user, $unitofmeasure)) return false;
        return $user->hasPermission('catalog.view', app(CompanyContext::class)->getCompany());
    }

    public function create(User $user): bool
    {
        $currentCompany = app(CompanyContext::class)->getCompany();
        if (!$currentCompany) return false;
        return $user->hasPermission('catalog.create', $currentCompany);
    }

    public function update(User $user, UnitOfMeasure $unitofmeasure): bool
    {
        if (!$this->checkCompany($user, $unitofmeasure)) return false;
        return $user->hasPermission('catalog.update', app(CompanyContext::class)->getCompany());
    }

    public function delete(User $user, UnitOfMeasure $unitofmeasure): bool
    {
        if (!$this->checkCompany($user, $unitofmeasure)) return false;
        return $user->hasPermission('catalog.delete', app(CompanyContext::class)->getCompany());
    }
}
