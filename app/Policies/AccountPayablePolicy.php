<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AccountPayable;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPayablePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('payables.view', $companyId);
    }

    public function view(User $user, AccountPayable $payable): bool
    {
        return $user->hasPermissionTo('payables.view', $payable->company_id);
    }

    public function createPayment(User $user, AccountPayable $payable): bool
    {
        return $user->hasPermissionTo('payables.payment.create', $payable->company_id);
    }
}
