<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Catalog\VehicleModel\StoreVehicleModelRequest;
use App\Http\Requests\Admin\Catalog\VehicleModel\UpdateVehicleModelRequest;
use App\Models\Catalog\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    public function index(): JsonResponse
    {
        $vehicleModels = VehicleModel::query()
            ->with([
                'brand',
                'category',
            ])
            ->orderBy('name')
            ->get();

        return response()->json([
            'vehicle_models' => $vehicleModels,
        ]);
    }

    public function store(StoreVehicleModelRequest $request): JsonResponse
    {
        $vehicleModel = VehicleModel::create($request->validated());

        return response()->json([
            'message' => 'Vehicle model created successfully.',
            'vehicle_model' => $vehicleModel,
        ], 201);
    }

    public function show(VehicleModel $vehicleModel): JsonResponse
    {
        $vehicleModel->load([
            'brand',
            'category',
            'vehicles',
        ]);

        return response()->json([
            'vehicle_model' => $vehicleModel,
        ]);
    }

    public function update(
        UpdateVehicleModelRequest $request,
        VehicleModel              $vehicleModel
    ): JsonResponse
    {
        $vehicleModel->update($request->validated());

        return response()->json([
            'message' => 'Vehicle model updated successfully.',
            'vehicle_model' => $vehicleModel->fresh(),
        ]);
    }

    public function destroy(VehicleModel $vehicleModel): JsonResponse
    {
        $vehicleModel->delete();

        return response()->json([
            'message' => 'Vehicle model deleted successfully.',
        ]);
    }
}
