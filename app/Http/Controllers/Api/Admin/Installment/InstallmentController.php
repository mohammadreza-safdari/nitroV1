<?php

namespace App\Http\Controllers\Api\Admin\Installment;

use App\Http\Controllers\Controller;
use App\Models\Sales\Deal;
use App\Models\Sales\Installment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index(Deal $deal): JsonResponse
    {
        $installments = $deal->installments()
            ->with('payment')
            ->latest()
            ->paginate(20);

        return response()->json([
            'installments' => $installments,
        ]);
    }

    public function show(Installment $installment): JsonResponse
    {
        $installment->load([
            'deal.user',
            'deal.vehicle.vehicleModel.brand',
            'payment',
        ]);

        return response()->json([
            'installment' => $installment,
        ]);
    }
}
