<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Catalog\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Admin\Catalog\Vehicle\UpdateVehicleRequest;
use App\Models\Catalog\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{

    public function index(): JsonResponse
    {
        $vehicles = Vehicle::query()
            ->with([
                'vehicleModel.brand',
                'vehicleModel.category',
                'images',
                'carDetail',
                'motorcycleDetail',
            ])
            ->latest()
            ->paginate(20);

        return response()->json([
            'vehicles' => $vehicles,
        ]);
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create($request->validated());

        return response()->json([
            'message' => 'Vehicle created successfully.',
            'vehicle' => $vehicle,
        ], 201);
    }

    public function show(Vehicle $vehicle): JsonResponse
    {
        $vehicle->load([
            'vehicleModel.brand',
            'vehicleModel.category',
            'images',
            'carDetail',
            'motorcycleDetail',
        ]);

        return response()->json([
            'vehicle' => $vehicle,
        ]);
    }

    public function update(
        UpdateVehicleRequest $request,
        Vehicle              $vehicle
    ): JsonResponse
    {
        $vehicle->update($request->validated());

        return response()->json([
            'message' => 'Vehicle updated successfully.',
            'vehicle' => $vehicle->fresh(),
        ]);
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();

        return response()->json([
            'message' => 'Vehicle deleted successfully.',
        ]);
    }
}

