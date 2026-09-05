<?php

namespace App\Policies;

use App\Models\AccountReceivable;
use App\Models\User;
use App\Context\CompanyContext;

class AccountReceivablePolicy
{
    protected function checkCompanyAccess(User $user, AccountReceivable $receivable): bool
    {
        $currentCompanyId = app(CompanyContext::class)->getCompanyId();
        return $receivable->company_id === $currentCompanyId && $user->belongsToCompany($currentCompanyId);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('receivables.view');
    }

    public function view(User $user, AccountReceivable $receivable): bool
    {
        return $this->checkCompanyAccess($user, $receivable) && $user->hasPermission('receivables.view');
    }

    public function registerPayment(User $user, AccountReceivable $receivable): bool
    {
        return $this->checkCompanyAccess($user, $receivable) && $user->hasPermission('receivables.payment.create');
    }
}
