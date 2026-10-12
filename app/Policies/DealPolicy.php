<?php

namespace App\Policies;

use App\Models\Sales\Deal;
use App\Models\User;

class DealPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Deal $deal): bool
    {
        return $user->is_admin
            || $user->id === $deal->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function approve(User $user, Deal $deal): bool
    {
        return $user->is_admin
            && $deal->status === 'pending';
    }

    public function reject(User $user, Deal $deal): bool
    {
        return $user->is_admin
            && $deal->status === 'pending';
    }

    public function finalize(User $user, Deal $deal): bool
    {
        return $user->is_admin
            && $deal->status === 'approved';
    }

    public function cancel(User $user, Deal $deal): bool
    {
        if ($user->is_admin) {
            return in_array($deal->status, [
                'pending',
                'approved',
            ]);
        }

        return $user->id === $deal->user_id;
    }

    public function update(User $user, Deal $deal): bool
    {
        return $user->id === $deal->user_id
            && in_array(
                $deal->status,
                ['pending', 'approved'],
                true
            );
    }

    public function delete(User $user, Deal $deal): bool
    {
        return false;
    }

    public function restore(User $user, Deal $deal): bool
    {
        return false;
    }

    public function forceDelete(User $user, Deal $deal): bool
    {
        return false;
    }
}
