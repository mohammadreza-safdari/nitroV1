<?php

namespace App\Http\Controllers\Api\Customer\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Payment\StorePaymentRequest;
use App\Http\Requests\Customer\Payment\UpdatePaymentRequest;
use App\Models\Sales\Deal;
use App\Models\Sales\Payment;
use App\Services\Sales\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(
        Deal $deal
    ): JsonResponse {
        $this->authorize(
            'viewDealPayments',
            [Payment::class, $deal]
        );

        $payments = $deal->payments()
            ->with('installments')
            ->latest()
            ->get();

        return response()->json([
            'payments' => $payments,
        ]);
    }

    public function show(
        Payment $payment
    ): JsonResponse {
        $this->authorize('view', $payment);

        $payment->load([
            'deal',
            'installments',
        ]);

        return response()->json([
            'payment' => $payment,
        ]);
    }

    public function store(
        StorePaymentRequest $request,
        PaymentService $paymentService
    ): JsonResponse {
        $deal = Deal::findOrFail(
            $request->integer('deal_id')
        );

        abort_if(
            $deal->status === 'finalized',
            422,
            'This deal has already been finalized.'
        );

        $this->authorize(
            'create',
            [Payment::class, $deal]
        );

        $payment = DB::transaction(function () use (
            $request,
            $deal,
            $paymentService
        ) {
            $existingActivePayment = $deal->payments()
                ->whereIn('status', ['pending', 'approved'])
                ->lockForUpdate()
                ->first();

            abort_if(
                $existingActivePayment,
                422,
                'An active payment request already exists for this deal.'
            );

            return $paymentService->create(
                $request,
                $deal
            );
        });

        return response()->json([
            'message' => 'Payment request submitted successfully.',
            'payment' => $payment,
        ], 201);
    }

    public function update(
        UpdatePaymentRequest $request,
        Payment $payment,
        PaymentService $paymentService
    ): JsonResponse {
        $payment->loadMissing('deal');

        abort_if(
            $payment->deal->status === 'finalized',
            422,
            'This deal has already been finalized.'
        );

        $this->authorize('update', $payment);

        $updatedPayment = DB::transaction(
            fn () => $paymentService->update(
                $request,
                $payment
            )
        );

        return response()->json([
            'message' =>
                'Payment request updated and resubmitted for review.',
            'payment' => $updatedPayment,
        ]);
    }
}
