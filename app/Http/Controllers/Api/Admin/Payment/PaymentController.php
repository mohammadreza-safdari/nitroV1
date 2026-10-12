<?php

namespace App\Http\Controllers\Api\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\Sales\Deal;
use App\Models\Sales\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Deal $deal): JsonResponse
    {
        $payments = $deal->payments()
            ->with('installments')
            ->latest()
            ->paginate(20);

        return response()->json([
            'payments' => $payments,
        ]);
    }

    public function show(Payment $payment): JsonResponse
    {
        $payment->load([
            'deal.user',
            'deal.vehicle.vehicleModel.brand',
            'deal.installments',
        ]);

        return response()->json([
            'payment' => $payment,
        ]);
    }

    public function confirm(Payment $payment): JsonResponse
    {
        $this->authorize('confirm', $payment);

        $payment->update([
            'status' => 'confirmed',
        ]);

        return response()->json([
            'message' => 'Payment confirmed successfully.',
            'payment' => $payment->fresh(),
        ]);
    }

    public function approve(Payment $payment): JsonResponse
    {
        $this->authorize('approve', $payment);

        $payment->update([
            'status' => 'approved',
        ]);

        return response()->json([
            'message' => 'Payment approved successfully.',
            'payment' => $payment->fresh(),
        ]);
    }

    public function reject(Payment $payment): JsonResponse
    {
        $this->authorize('reject', $payment);

        $payment->update([
            'status' => 'rejected',
        ]);

        return response()->json([
            'message' => 'Payment rejected successfully.',
            'payment' => $payment->fresh(),
        ]);
    }
}
