<?php

namespace App\Http\Controllers\Api\Customer\Deal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\buy\StoreDealRequest;
use App\Http\Requests\Customer\buy\UpdateDealRequest;
use App\Models\Catalog\Vehicle;
use App\Models\Sales\Deal;
use App\Models\Sales\DiscountCode;
use App\Services\Sales\DealService;
use App\Services\Sales\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $deals = $request->user()
            ->deals()
            ->with([
                'vehicle.vehicleModel.brand',
                'vehicle.images',
                'address',
                'discountCode',
                'changeRequests',
            ])
            ->latest()
            ->paginate(20);

        return response()->json([
            'deals' => $deals,
        ]);
    }

    public function show(Deal $deal): JsonResponse
    {
        $this->authorize('view', $deal);

        $deal->load([
            'vehicle.vehicleModel.brand',
            'vehicle.images',
            'address',
            'discountCode',
            'payments',
            'installments',
            'events',
            'changeRequests.address',
            'changeRequests.discountCode',
        ]);

        return response()->json([
            'deal' => $deal,
        ]);
    }

    public function store(
        StoreDealRequest $request,
        PricingService $pricingService,
        DealService $dealService
    ): JsonResponse {
        $this->authorize('create', Deal::class);

        $vehicle = Vehicle::findOrFail(
            $request->integer('vehicle_id')
        );

        if ($vehicle->hasActivePreOrderOffer()) {
            return response()->json([
                'message' =>
                    'This vehicle is currently available for pre-order only.',
            ], 422);
        }

        $pricing = $pricingService->calculate(
            $vehicle,
            $request->input('discount_code')
        );

        $discountCode = null;

        if (! empty($pricing['discount_code'])) {
            $discountCode = DiscountCode::query()
                ->where('code', $pricing['discount_code'])
                ->first();
        }

        $result = $dealService->create(
            $request->user()->id,
            $vehicle,
            $request->validated(),
            $pricing,
            $discountCode
        );

        if (! $result['created']) {
            return response()->json([
                'message' =>
                    'An active purchase request already exists for this vehicle. Submit a change request instead.',
                'deal' => $result['deal'],
            ], 409);
        }

        return response()->json([
            'message' => 'Purchase request submitted successfully.',
            'deal' => $result['deal'],
            'pricing' => $pricing,
        ], 201);
    }

    public function update(
        UpdateDealRequest $request,
        Deal $deal,
        PricingService $pricingService,
        DealService $dealService
    ): JsonResponse {
        $this->authorize('update', $deal);

        if ($deal->type !== 'purchase') {
            return response()->json([
                'message' =>
                    'Only purchase deals can have their terms changed through this endpoint.',
            ], 422);
        }

        if ($deal->vehicle->hasActivePreOrderOffer()) {
            return response()->json([
                'message' =>
                    'This vehicle is currently available for pre-order only.',
            ], 422);
        }

        $pricing = $pricingService->calculate(
            $deal->vehicle,
            $request->input('discount_code')
        );

        $discountCode = null;

        if (! empty($pricing['discount_code'])) {
            $discountCode = DiscountCode::query()
                ->where('code', $pricing['discount_code'])
                ->first();
        }

        $changeRequest = $dealService->requestUpdate(
            $deal,
            $request->user()->id,
            $request->validated(),
            $pricing,
            $discountCode
        );

        return response()->json([
            'message' =>
                'Your purchase terms change request has been submitted for admin approval.',
            'deal' => $deal->fresh(),
            'change_request' => $changeRequest,
            'requires_admin_approval' => true,
        ], 202);
    }
}
