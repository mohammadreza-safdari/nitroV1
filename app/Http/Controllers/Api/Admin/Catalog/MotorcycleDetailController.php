<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Catalog\MotorcycleDetail\StoreMotorcycleDetailRequest;
use App\Http\Requests\Admin\Catalog\MotorcycleDetail\UpdateMotorcycleDetailRequest;
use App\Models\Catalog\MotorcycleDetail;
use App\Models\Catalog\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MotorcycleDetailController extends Controller
{
    public function store(
        StoreMotorcycleDetailRequest $request,
        Vehicle                      $vehicle
    ): JsonResponse
    {
        $motorcycleDetail = $vehicle->motorcycleDetail()->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Motorcycle details created successfully.',
            'motorcycle_detail' => $motorcycleDetail,
        ], 201);
    }

    public function update(
        UpdateMotorcycleDetailRequest $request,
        MotorcycleDetail              $motorcycleDetail
    ): JsonResponse
    {
        $motorcycleDetail->update($request->validated());

        return response()->json([
            'message' => 'Motorcycle details updated successfully.',
            'motorcycle_detail' => $motorcycleDetail->fresh(),
        ]);
    }

    public function destroy(MotorcycleDetail $motorcycleDetail): JsonResponse
    {
        $motorcycleDetail->delete();

        return response()->json([
            'message' => 'Motorcycle details deleted successfully.',
        ]);
    }
}
