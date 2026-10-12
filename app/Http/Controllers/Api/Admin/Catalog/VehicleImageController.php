<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Catalog\VehicleImage\StoreVehicleImageRequest;
use App\Http\Requests\Admin\Catalog\VehicleImage\UpdateVehicleImageRequest;
use App\Models\Catalog\Vehicle;
use App\Models\Catalog\VehicleImage;
use App\Services\Catalog\VehicleImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleImageController extends Controller
{
    public function __construct(
        private VehicleImageService $vehicleImageService
    ) {
    }

    public function store(
        StoreVehicleImageRequest $request,
        Vehicle                  $vehicle
    ): JsonResponse
    {
        $vehicleImage = $this->vehicleImageService->store(
            $vehicle,
            $request->file('image'),
            $request->boolean('is_primary'),
            $request->input('sort_order', 0)
        );

        return response()->json([
            'message' => 'Vehicle image uploaded successfully.',
            'vehicle_image' => $vehicleImage,
        ], 201);
    }

    public function update(
        UpdateVehicleImageRequest $request,
        VehicleImage              $vehicleImage
    ): JsonResponse
    {
        $vehicleImage = $this->vehicleImageService->update(
            $vehicleImage,
            $request->file('image'),
            $request->boolean('is_primary'),
            $request->input('sort_order', $vehicleImage->sort_order)
        );

        return response()->json([
            'message' => 'Vehicle image updated successfully.',
            'vehicle_image' => $vehicleImage,
        ]);
    }

    public function destroy(VehicleImage $vehicleImage): JsonResponse
    {
        $this->vehicleImageService->delete($vehicleImage);

        return response()->json([
            'message' => 'Vehicle image deleted successfully.',
        ]);
    }
}
