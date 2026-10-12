<?php

namespace App\Policies;

use App\Models\Sales\Deal;
use App\Models\Sales\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(
        User $user,
        Payment $payment
    ): bool {
        return $user->is_admin
            || $user->id === $payment->deal->user_id;
    }

    public function viewDealPayments(
        User $user,
        Deal $deal
    ): bool {
        return $user->is_admin
            || $user->id === $deal->user_id;
    }

    public function create(
        User $user,
        Deal $deal
    ): bool {
        return $user->id === $deal->user_id
            && $deal->status === 'approved';
    }

    public function update(
        User $user,
        Payment $payment
    ): bool {
        return $user->id === $payment->deal->user_id
            && $payment->deal->status === 'approved'
            && in_array(
                $payment->status,
                ['pending', 'approved'],
                true
            );
    }

    public function approve(
        User $user,
        Payment $payment
    ): bool {
        return $user->is_admin
            && $payment->status === 'pending';
    }

    public function reject(
        User $user,
        Payment $payment
    ): bool {
        return $user->is_admin
            && $payment->status === 'pending';
    }

    public function cancel(
        User $user,
        Payment $payment
    ): bool {
        return $user->is_admin
            && $payment->status === 'pending';
    }

    public function confirm(
        User $user,
        Payment $payment
    ): bool {
        return $user->is_admin
            && $payment->status === 'paid';
    }

    public function delete(
        User $user,
        Payment $payment
    ): bool {
        return false;
    }

    public function restore(
        User $user,
        Payment $payment
    ): bool {
        return false;
    }

    public function forceDelete(
        User $user,
        Payment $payment
    ): bool {
        return false;
    }
}
