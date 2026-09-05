<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Purchase;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('purchases.view', $companyId);
    }

    public function view(User $user, Purchase $purchase): bool
    {
        return $user->hasPermissionTo('purchases.view', $purchase->company_id);
    }

    public function create(User $user, int $companyId): bool
    {
        return $user->hasPermissionTo('purchases.create', $companyId);
    }

    public function update(User $user, Purchase $purchase): bool
    {
        return $user->hasPermissionTo('purchases.update', $purchase->company_id);
    }

    public function confirm(User $user, Purchase $purchase): bool
    {
        return $user->hasPermissionTo('purchases.confirm', $purchase->company_id);
    }

    public function cancel(User $user, Purchase $purchase): bool
    {
        return $user->hasPermissionTo('purchases.cancel', $purchase->company_id);
    }
}
