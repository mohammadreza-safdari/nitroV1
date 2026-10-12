<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Catalog\CarDetail\StoreCarDetailRequest;
use App\Http\Requests\Admin\Catalog\CarDetail\UpdateCarDetailRequest;
use App\Models\Catalog\CarDetail;
use App\Models\Catalog\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarDetailController extends Controller
{
    public function store(
        StoreCarDetailRequest $request,
        Vehicle               $vehicle
    ): JsonResponse
    {
        $carDetail = $vehicle->carDetail()->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Car details created successfully.',
            'car_detail' => $carDetail,
        ], 201);
    }

    public function update(
        UpdateCarDetailRequest $request,
        CarDetail              $carDetail
    ): JsonResponse
    {
        $carDetail->update($request->validated());

        return response()->json([
            'message' => 'Car details updated successfully.',
            'car_detail' => $carDetail->fresh(),
        ]);
    }

    public function destroy(CarDetail $carDetail): JsonResponse
    {
        $carDetail->delete();

        return response()->json([
            'message' => 'Car details deleted successfully.',
        ]);
    }
}
