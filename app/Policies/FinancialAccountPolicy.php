<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FinancialAccount;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancialAccountPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('treasury.view', $companyId);
    }

    public function view(User $user, FinancialAccount $account): bool
    {
        return $user->hasPermissionTo('treasury.view', $account->company_id);
    }

    public function manage(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('treasury.manage_accounts', $companyId);
    }
}
