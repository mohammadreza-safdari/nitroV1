<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\buy\CalculatePriceRequest;
use App\Models\Catalog\Vehicle;
use App\Services\Sales\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $now = now();

        $vehicles = Vehicle::query()
            ->with([
                'vehicleModel.brand',
                'vehicleModel.category',
                'images',
                'offers' => function ($query) use ($now) {
                    $query
                        ->where('is_active', true)
                        ->where(function ($query) use ($now) {
                            $query->whereNull('starts_at')
                                ->orWhere('starts_at', '<=', $now);
                        })
                        ->where(function ($query) use ($now) {
                            $query->whereNull('ends_at')
                                ->orWhere('ends_at', '>=', $now);
                        });
                },
            ])
            ->when($request->filled('brand_id'), function ($query) use ($request) {
                $query->whereHas('vehicleModel', function ($query) use ($request) {
                    $query->where('brand_id', $request->brand_id);
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->whereHas('vehicleModel', function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                });
            })
            ->when($request->filled('vehicle_model_id'), function ($query) use ($request) {
                $query->where('vehicle_model_id', $request->vehicle_model_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('condition'), function ($query) use ($request) {
                $query->where('condition', $request->condition);
            })
            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('price', '>=', $request->min_price);
            })
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', $request->max_price);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('vehicleModel', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->orderByRaw("
            EXISTS (
                SELECT 1
                FROM offers
                WHERE offers.vehicle_id = vehicles.id
                AND offers.type = 'featured'
                AND offers.is_active = 1
                AND (offers.starts_at IS NULL OR offers.starts_at <= ?)
                AND (offers.ends_at IS NULL OR offers.ends_at >= ?)
            ) DESC
        ", [$now, $now])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'vehicles' => $vehicles,
        ]);
    }

    public function show(Vehicle $vehicle): JsonResponse
    {
        $vehicle->load([
            'vehicleModel.brand',
            'vehicleModel.category',
            'images',
            'carDetail',
            'motorcycleDetail',
            'offers' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('starts_at')
                            ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('ends_at')
                            ->orWhere('ends_at', '>=', now());
                    });
            },
        ]);

        return response()->json([
            'vehicle' => $vehicle,
        ]);
    }

    public function calculatePrice(
        CalculatePriceRequest $request,
        Vehicle $vehicle,
        PricingService $pricingService
    ): JsonResponse {
        $pricing = $pricingService->calculate(
            $vehicle,
            $request->input('discount_code')
        );

        return response()->json([
            'pricing' => $pricing,
        ]);
    }
}
