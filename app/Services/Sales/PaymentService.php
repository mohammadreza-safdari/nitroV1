<?php

namespace App\Services\Sales;

use App\Models\Sales\Deal;
use App\Models\Sales\Installment;
use App\Models\Sales\Payment;
use App\Http\Requests\Customer\Payment\StorePaymentRequest;
use App\Http\Requests\Customer\Payment\UpdatePaymentRequest;

class PaymentService
{
    public function create(
        StorePaymentRequest $request,
        Deal $deal
    ): Payment {
        if ($deal->payment_method === 'installment') {
            return $this->createInstallmentPayment(
                $request,
                $deal
            );
        }

        return $this->createFullPayment(
            $request,
            $deal
        );
    }

    public function update(
        UpdatePaymentRequest $request,
        Payment $payment
    ): Payment {
        $payment->refresh();

        $deal = $payment->deal;

        if ($payment->status === 'approved') {
            $payment->update([
                'status' => 'pending',
            ]);
        }

        if ($deal->payment_method === 'installment') {
            $payment->installments()
                ->update([
                    'payment_id' => null,
                ]);

            return $this->updateInstallmentPayment(
                $request,
                $payment,
                $deal
            );
        }

        return $this->updateFullPayment(
            $request,
            $payment,
            $deal
        );
    }

    private function createFullPayment(
        StorePaymentRequest $request,
        Deal $deal
    ): Payment {
        /** @var Payment $payment */
        $payment = $deal->payments()->create([
            'amount' => $deal->final_amount,
            'method' => $request->string('method')->toString(),
            'status' => 'pending',
            'transaction_reference' =>
                $request->input('transaction_reference'),
        ]);

        return $payment;
    }

    private function createInstallmentPayment(
        StorePaymentRequest $request,
        Deal $deal
    ): Payment {
        $installmentIds = $request->input(
            'installment_ids',
            []
        );

        abort_if(
            empty($installmentIds),
            422,
            'At least one installment must be selected.'
        );

        $installments = Installment::query()
            ->where('deal_id', $deal->id)
            ->whereIn('id', $installmentIds)
            ->where('status', 'pending')
            ->whereNull('payment_id')
            ->lockForUpdate()
            ->get();

        abort_if(
            $installments->count() !== count($installmentIds),
            422,
            'One or more selected installments are not available.'
        );

        $amount = $installments->sum('amount');

        /** @var Payment $payment */
        $payment = $deal->payments()->create([
            'amount' => $amount,
            'method' => $request->string('method')->toString(),
            'status' => 'pending',
            'transaction_reference' =>
                $request->input('transaction_reference'),
        ]);

        $installments->each(function (
            Installment $installment
        ) use ($payment) {
            $installment->update([
                'payment_id' => $payment->id,
            ]);
        });

        return $payment->load('installments');
    }

    private function updateFullPayment(
        UpdatePaymentRequest $request,
        Payment $payment,
        Deal $deal
    ): Payment {
        $payment->update([
            'amount' => $deal->final_amount,
            'method' => $request->string('method')->toString(),
            'transaction_reference' =>
                $request->input('transaction_reference'),
        ]);

        return $payment->fresh([
            'deal',
            'installments',
        ]);
    }

    private function updateInstallmentPayment(
        UpdatePaymentRequest $request,
        Payment $payment,
        Deal $deal
    ): Payment {
        $installmentIds = $request->input(
            'installment_ids',
            []
        );

        abort_if(
            empty($installmentIds),
            422,
            'At least one installment must be selected.'
        );

        $installments = Installment::query()
            ->where('deal_id', $deal->id)
            ->whereIn('id', $installmentIds)
            ->where('status', 'pending')
            ->whereNull('payment_id')
            ->lockForUpdate()
            ->get();

        abort_if(
            $installments->count() !== count($installmentIds),
            422,
            'One or more selected installments are not available.'
        );

        $amount = $installments->sum('amount');

        $payment->update([
            'amount' => $amount,
            'method' => $request->string('method')->toString(),
            'transaction_reference' =>
                $request->input('transaction_reference'),
        ]);

        $installments->each(function (
            Installment $installment
        ) use ($payment) {
            $installment->update([
                'payment_id' => $payment->id,
            ]);
        });

        return $payment->fresh([
            'deal',
            'installments',
        ]);
    }
}
