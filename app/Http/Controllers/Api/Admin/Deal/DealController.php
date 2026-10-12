<?php

namespace App\Http\Controllers\Api\Admin\Deal;

use App\Http\Controllers\Controller;
use App\Models\Sales\Deal;
use App\Models\Sales\DealChangeRequest;
use App\Services\Sales\AdminDealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(
        private AdminDealService $adminDealService
    ) {
    }

    public function index(): JsonResponse
    {
        $deals = Deal::query()
            ->with([
                'user',
                'vehicle.vehicleModel.brand',
                'vehicle.images',
                'address',
                'discountCode',
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
            'user',
            'vehicle.vehicleModel.brand',
            'vehicle.images',
            'address',
            'discountCode',
            'payments',
            'installments',
            'changeRequests.user',
            'changeRequests.address',
            'changeRequests.discountCode',
            'events.user',
        ]);

        return response()->json([
            'deal' => $deal,
        ]);
    }

    public function approve(Request $request, Deal $deal): JsonResponse
    {
        $this->authorize('approve', $deal);

        $deal = $this->adminDealService->approve(
            $deal,
            $request->user()
        );

        return response()->json([
            'message' => 'Deal approved successfully.',
            'deal' => $deal,
        ]);
    }

    public function reject(Request $request, Deal $deal): JsonResponse
    {
        $this->authorize('reject', $deal);

        $deal = $this->adminDealService->reject(
            $deal,
            $request->user()
        );

        return response()->json([
            'message' => 'Deal rejected successfully.',
            'deal' => $deal,
        ]);
    }

    public function finalize(Request $request, Deal $deal): JsonResponse
    {
        $this->authorize('finalize', $deal);

        $deal = $this->adminDealService->finalize(
            $deal,
            $request->user()
        );

        return response()->json([
            'message' => 'Deal finalized successfully.',
            'deal' => $deal,
        ]);
    }

    public function cancel(Request $request, Deal $deal): JsonResponse
    {
        $this->authorize('cancel', $deal);

        $deal = $this->adminDealService->cancel(
            $deal,
            $request->user()
        );

        return response()->json([
            'message' => 'Deal cancelled successfully.',
            'deal' => $deal,
        ]);
    }

    public function approveChangeRequest(
        Request $request,
        DealChangeRequest $changeRequest
    ): JsonResponse {
        $this->authorize('approve', $changeRequest);

        $changeRequest = $this->adminDealService->approveChangeRequest(
            $changeRequest,
            $request->user()
        );

        return response()->json([
            'message' => 'Deal change request approved successfully.',
            'change_request' => $changeRequest,
            'deal' => $changeRequest->deal,
        ]);
    }

    public function rejectChangeRequest(
        Request $request,
        DealChangeRequest $changeRequest
    ): JsonResponse {
        $this->authorize('reject', $changeRequest);

        $changeRequest = $this->adminDealService->rejectChangeRequest(
            $changeRequest,
            $request->user()
        );

        return response()->json([
            'message' => 'Deal change request rejected successfully.',
            'change_request' => $changeRequest,
            'deal' => $changeRequest->deal,
        ]);
    }
}
