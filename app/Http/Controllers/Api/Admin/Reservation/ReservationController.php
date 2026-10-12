<?php

namespace App\Http\Controllers\Api\Admin\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Sales\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(): JsonResponse
    {
        $reservations = Reservation::query()
            ->with([
                'user',
                'vehicle.vehicleModel.brand',
                'vehicle.images',
            ])
            ->latest()
            ->paginate(20);

        return response()->json([
            'reservations' => $reservations,
        ]);
    }

    public function show(Reservation $reservation): JsonResponse
    {
        $reservation->load([
            'user',
            'vehicle.vehicleModel.brand',
            'vehicle.images',
        ]);

        return response()->json([
            'reservation' => $reservation,
        ]);
    }

    public function approve(Reservation $reservation): JsonResponse
    {
        $this->authorize('approve', $reservation);

        $reservation->update([
            'status' => 'approved',
        ]);

        return response()->json([
            'message' => 'Reservation approved successfully.',
            'reservation' => $reservation->fresh(),
        ]);
    }

    public function reject(Reservation $reservation): JsonResponse
    {
        $this->authorize('reject', $reservation);

        $reservation->update([
            'status' => 'rejected',
        ]);

        $reservation->vehicle->update([
            'status' => 'available',
        ]);

        return response()->json([
            'message' => 'Reservation rejected successfully.',
            'reservation' => $reservation->fresh(),
        ]);
    }

    public function cancel(Reservation $reservation): JsonResponse
    {
        $this->authorize('cancel', $reservation);

        $reservation->update([
            'status' => 'cancelled',
        ]);

        $reservation->vehicle->update([
            'status' => 'available',
        ]);

        return response()->json([
            'message' => 'Reservation cancelled successfully.',
        ]);
    }
}
