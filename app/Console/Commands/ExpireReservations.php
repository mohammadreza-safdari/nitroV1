<?php

namespace App\Console\Commands;

use App\Models\Sales\Reservation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('reservations:expire')]
#[Description('Expire reservations that have passed their reserved time')]
class ExpireReservations extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reservations = Reservation::query()
            ->where('status', 'pending')
            ->where('reserved_until', '<=', now())
            ->with('vehicle')
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->update([
                'status' => 'expired',
            ]);

            $reservation->vehicle->update([
                'status' => 'available',
            ]);
        }

        $this->info("Expired {$reservations->count()} reservations.");

        return self::SUCCESS;
    }
}
