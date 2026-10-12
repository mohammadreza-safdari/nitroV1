<?php

namespace App\Http\Controllers\Api\Customer\PreOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\PreOrder\StorePreOrderRequest;
use App\Models\Catalog\Vehicle;
use App\Models\Sales\PreOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $preOrders = $request->user()
            ->preOrders()
            ->with([
                'vehicle.vehicleModel.brand',
                'vehicle.images',
                'address',
            ])
            ->latest()
            ->paginate(20);

        return response()->json([
            'pre_orders' => $preOrders,
        ]);
    }

    public function store(
        StorePreOrderRequest $request
    ): JsonResponse {
        $this->authorize(
            'create',
            PreOrder::class
        );

        $vehicle = Vehicle::findOrFail(
            $request->integer('vehicle_id')
        );

        if (! $vehicle->hasActivePreOrderOffer()) {
            return response()->json([
                'message' =>
                    'This vehicle is not currently available for pre-order.',
            ], 422);
        }

        $preOrder = $request->user()
            ->preOrders()
            ->create($request->validated());

        return response()->json([
            'message' =>
                'Pre-order created successfully.',
            'pre_order' => $preOrder->load([
                'vehicle.vehicleModel.brand',
                'vehicle.images',
                'address',
            ]),
        ], 201);
    }

    public function show(
        PreOrder $preOrder
    ): JsonResponse {
        $this->authorize('view', $preOrder);

        $preOrder->load([
            'vehicle.vehicleModel.brand',
            'vehicle.images',
            'address',
        ]);

        return response()->json([
            'pre_order' => $preOrder,
        ]);
    }

    public function cancel(
        PreOrder $preOrder
    ): JsonResponse {
        $this->authorize('cancel', $preOrder);

        $preOrder->update([
            'status' => 'cancelled',
        ]);

        return response()->json([
            'message' =>
                'Pre-order cancelled successfully.',
            'pre_order' => $preOrder->fresh(),
        ]);
    }
}
