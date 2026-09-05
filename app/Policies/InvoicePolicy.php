<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Context\CompanyContext;

class InvoicePolicy
{
    protected function checkCompanyAccess(User $user, Invoice $invoice): bool
    {
        $currentCompanyId = app(CompanyContext::class)->getCompanyId();
        return $invoice->company_id === $currentCompanyId && $user->belongsToCompany($currentCompanyId);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('billing.view');
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->checkCompanyAccess($user, $invoice) && $user->hasPermission('billing.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('billing.create');
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $this->checkCompanyAccess($user, $invoice) && $user->hasPermission('billing.create');
    }

    public function confirm(User $user, Invoice $invoice): bool
    {
        return $this->checkCompanyAccess($user, $invoice) && $user->hasPermission('billing.confirm');
    }

    public function cancel(User $user, Invoice $invoice): bool
    {
        return $this->checkCompanyAccess($user, $invoice) && $user->hasPermission('billing.cancel');
    }
}
