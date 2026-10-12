<?php

namespace App\Http\Controllers\Api\Customer\Installment;

use App\Http\Controllers\Controller;
use App\Models\Sales\Deal;
use App\Models\Sales\Installment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{

    public function index(Deal $deal): JsonResponse
    {
        $this->authorize('view', $deal);

        $installments = $deal->installments()
            ->with('payment')
            ->orderBy('installment_number')
            ->get();

        return response()->json([
            'installments' => $installments,
        ]);
    }

    public function show(Installment $installment): JsonResponse
    {
        $this->authorize('view', $installment);

        $installment->load([
            'deal',
            'payment',
        ]);

        return response()->json([
            'installment' => $installment,
        ]);
    }
}
