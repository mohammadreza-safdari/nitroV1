<?php

namespace App\Policies;

use App\Models\Sales\PreOrder;
use App\Models\User;

class PreOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(
        User $user,
        PreOrder $preOrder
    ): bool {
        return $user->is_admin
            || $preOrder->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(
        User $user,
        PreOrder $preOrder
    ): bool {
        return false;
    }

    public function delete(
        User $user,
        PreOrder $preOrder
    ): bool {
        return false;
    }

    public function restore(
        User $user,
        PreOrder $preOrder
    ): bool {
        return false;
    }

    public function forceDelete(
        User $user,
        PreOrder $preOrder
    ): bool {
        return false;
    }

    public function cancel(
        User $user,
        PreOrder $preOrder
    ): bool {
        return $user->id === $preOrder->user_id
            && in_array($preOrder->status, ['pending', 'approved'], true);
    }

    public function approve(
        User $user,
        PreOrder $preOrder
    ): bool {
        return $user->is_admin
            && $preOrder->status === 'pending';
    }

    public function reject(
        User $user,
        PreOrder $preOrder
    ): bool {
        return $user->is_admin
            && $preOrder->status === 'pending';
    }
}
