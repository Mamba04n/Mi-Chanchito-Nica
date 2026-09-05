<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FinancialMovement;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancialMovementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('treasury.view', $companyId);
    }

    public function createIncome(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('treasury.income.create', $companyId);
    }

    public function createExpense(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('treasury.expense.create', $companyId);
    }

    public function transfer(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('treasury.transfer', $companyId);
    }

    public function adjust(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('treasury.adjust', $companyId);
    }
}
