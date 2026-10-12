<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Offer;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    public function index(): JsonResponse
    {
        $offers = Offer::query()
            ->with([
                'vehicle',
                'vehicle.vehicleModel.brand',
                'vehicle.images',
            ])
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->get();

        return response()->json($offers);
    }

    public function show(Offer $offer): JsonResponse
    {
        $offer->load([
            'vehicle',
            'vehicle.vehicleModel.brand',
            'vehicle.vehicleModel.category',
            'vehicle.images',
        ]);

        return response()->json([
            'offer' => $offer,
        ]);
    }
}
