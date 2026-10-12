<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Catalog\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    public function index(): JsonResponse
    {
        $vehicleModels = VehicleModel::query()
            ->with(['brand', 'category'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'vehicle_models' => $vehicleModels,
        ]);
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
}
