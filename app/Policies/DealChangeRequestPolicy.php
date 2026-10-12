<?php

namespace App\Policies;

use App\Models\Sales\DealChangeRequest;
use App\Models\User;

class DealChangeRequestPolicy
{
    public function approve(User $user, DealChangeRequest $changeRequest): bool
    {
        return $user->is_admin
            && $changeRequest->status === 'pending';
    }

    public function reject(User $user, DealChangeRequest $changeRequest): bool
    {
        return $user->is_admin
            && $changeRequest->status === 'pending';
    }
}
