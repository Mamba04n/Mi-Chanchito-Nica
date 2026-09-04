<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Context\CompanyContext;

class CustomerPolicy
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
        return $user->hasPermission('customers.view', $currentCompany);
    }

    public function view(User $user, Customer $customer): bool
    {
        if (!$this->checkCompany($user, $customer)) return false;
        return $user->hasPermission('customers.view', app(CompanyContext::class)->getCompany());
    }

    public function create(User $user): bool
    {
        $currentCompany = app(CompanyContext::class)->getCompany();
        if (!$currentCompany) return false;
        return $user->hasPermission('customers.create', $currentCompany);
    }

    public function update(User $user, Customer $customer): bool
    {
        if (!$this->checkCompany($user, $customer)) return false;
        return $user->hasPermission('customers.update', app(CompanyContext::class)->getCompany());
    }

    public function delete(User $user, Customer $customer): bool
    {
        if (!$this->checkCompany($user, $customer)) return false;
        return $user->hasPermission('customers.delete', app(CompanyContext::class)->getCompany());
    }
}
