<?php

namespace App\Http\Controllers\Api\Customer\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Reservation\StoreReservationRequest;
use App\Models\Catalog\Vehicle;
use App\Models\Sales\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $reservations = $request->user()
            ->reservations()
            ->with([
                'vehicle.vehicleModel.brand',
                'vehicle.images',
            ])
            ->latest()
            ->paginate(20);

        return response()->json([
            'reservations' => $reservations,
        ]);
    }

    public function store(
        StoreReservationRequest $request
    ): JsonResponse {
        $this->authorize(
            'create',
            Reservation::class
        );

        $vehicle = Vehicle::findOrFail(
            $request->integer('vehicle_id')
        );

        if ($vehicle->hasActivePreOrderOffer()) {
            return response()->json([
                'message' =>
                    'This vehicle is currently available for pre-order only.',
            ], 422);
        }

        if ($vehicle->status !== 'available') {
            return response()->json([
                'message' =>
                    'This vehicle is not available for reservation.',
            ], 422);
        }

        $reservation = $request->user()
            ->reservations()
            ->create([
                ...$request->validated(),
                'reserved_until' => now()->addDays(14),
            ]);

        $vehicle->update([
            'status' => 'reserved',
        ]);

        return response()->json([
            'message' =>
                'Reservation created successfully.',
            'reservation' => $reservation,
        ], 201);
    }

    public function show(
        Reservation $reservation
    ): JsonResponse {
        $this->authorize('view', $reservation);

        $reservation->load([
            'vehicle.vehicleModel.brand',
            'vehicle.images',
        ]);

        return response()->json([
            'reservation' => $reservation,
        ]);
    }

    public function cancel(
        Reservation $reservation
    ): JsonResponse {
        $this->authorize('cancel', $reservation);

        if ($reservation->status !== 'pending') {
            return response()->json([
                'message' =>
                    'This reservation cannot be cancelled.',
            ], 422);
        }

        $reservation->update([
            'status' => 'cancelled',
        ]);

        $reservation->vehicle->update([
            'status' => 'available',
        ]);

        return response()->json([
            'message' =>
                'Reservation cancelled successfully.',
        ]);
    }
}
