<?php

namespace App\Policies;

use App\Models\Sales\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $user->is_admin
            || $user->id === $reservation->user_id;
    }

    public function approve(User $user, Reservation $reservation): bool
    {
        return $user->is_admin
            && $reservation->status === 'pending'
            && $reservation->reserved_until->isFuture();
    }

    public function reject(User $user, Reservation $reservation): bool
    {
        return $user->is_admin
            && $reservation->status === 'pending';
    }

    public function cancel(User $user, Reservation $reservation): bool
    {
        return $user->is_admin
            || $user->id === $reservation->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return false;
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return false;
    }

    public function restore(User $user, Reservation $reservation): bool
    {
        return false;
    }

    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return false;
    }
}
