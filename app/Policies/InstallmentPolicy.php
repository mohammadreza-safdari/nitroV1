<?php

namespace App\Policies;

use App\Models\Sales\Installment;
use App\Models\User;

class InstallmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Installment $installment): bool
    {
        return $user->is_admin
            || $user->id === $installment->deal->user_id;
    }

    public function markPaid(User $user, Installment $installment): bool
    {
        return $user->is_admin
            && $installment->status === 'pending';
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Installment $installment): bool
    {
        return false;
    }

    public function delete(User $user, Installment $installment): bool
    {
        return false;
    }

    public function restore(User $user, Installment $installment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Installment $installment): bool
    {
        return false;
    }
}
