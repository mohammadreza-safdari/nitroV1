<?php

namespace App\Services\Sales;

use App\Events\DealRequestProcessed;
use App\Models\Sales\Deal;
use App\Models\Sales\DealChangeRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminDealService
{
    public function approve(Deal $deal, User $admin): Deal
    {
        return DB::transaction(function () use ($deal, $admin) {
            $deal = Deal::query()
                ->lockForUpdate()
                ->findOrFail($deal->id);

            $this->ensureDealStatus($deal, ['pending']);

            $deal->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            $deal = $deal->fresh(['user']);

            DealRequestProcessed::dispatch(
                $deal,
                $deal->user,
                'deal',
                'approved'
            );

            return $deal;
        });
    }

    public function reject(Deal $deal, User $admin): Deal
    {
        return DB::transaction(function () use ($deal, $admin) {
            $deal = Deal::query()
                ->lockForUpdate()
                ->findOrFail($deal->id);

            $this->ensureDealStatus($deal, ['pending']);

            $deal->update([
                'status' => 'rejected',
            ]);

            $deal = $deal->fresh(['user']);

            DealRequestProcessed::dispatch(
                $deal,
                $deal->user,
                'deal',
                'rejected'
            );

            return $deal;
        });
    }

    public function finalize(Deal $deal, User $admin): Deal
    {
        return DB::transaction(function () use ($deal, $admin) {
            $deal = Deal::query()
                ->lockForUpdate()
                ->findOrFail($deal->id);

            $this->ensureDealStatus($deal, ['approved']);

            $deal->update([
                'status' => 'finalized',
                'finalized_at' => now(),
            ]);

            return $deal->fresh();
        });
    }

    public function cancel(Deal $deal, User $admin): Deal
    {
        return DB::transaction(function () use ($deal, $admin) {
            $deal = Deal::query()
                ->lockForUpdate()
                ->findOrFail($deal->id);

            $this->ensureDealStatus($deal, ['pending', 'approved']);

            $deal->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            return $deal->fresh();
        });
    }

    public function approveChangeRequest(
        DealChangeRequest $changeRequest,
        User $admin
    ): DealChangeRequest {
        return DB::transaction(function () use ($changeRequest, $admin) {
            $changeRequest = DealChangeRequest::query()
                ->lockForUpdate()
                ->findOrFail($changeRequest->id);

            if ($changeRequest->status !== 'pending') {
                throw ValidationException::withMessages([
                    'change_request' => 'This change request has already been reviewed.',
                ]);
            }

            $deal = Deal::query()
                ->lockForUpdate()
                ->findOrFail($changeRequest->deal_id);

            $this->ensureDealStatus($deal, ['pending', 'approved']);

            $newTotal = round(
                (float) $changeRequest->final_amount,
                2
            );

            $paidAmount = round(
                (float) $deal->payments()
                    ->where('status', 'paid')
                    ->sum('amount'),
                2
            );

            $remainingAmount = round(
                $newTotal - $paidAmount,
                2
            );

            if ($remainingAmount < 0) {
                throw ValidationException::withMessages([
                    'change_request' => 'The amount already paid exceeds the new contract total.',
                ]);
            }

            if ($changeRequest->payment_method === 'installment') {
                $months = (int) $changeRequest->installment_months;

                if ($months < 1 || $months > 255) {
                    throw ValidationException::withMessages([
                        'installment_months' => 'The installment duration is invalid.',
                    ]);
                }

                if ($remainingAmount <= 0) {
                    throw ValidationException::withMessages([
                        'change_request' => 'An installment plan cannot be created when no balance remains.',
                    ]);
                }
            }

            $adminNote = trim(implode("\n\n", array_filter([
                $deal->admin_note,
                sprintf(
                    'Deal terms updated by admin on %s. New contract total: %s. Amount paid: %s. Remaining balance: %s.',
                    now()->toDateTimeString(),
                    number_format($newTotal, 2, '.', ''),
                    number_format($paidAmount, 2, '.', ''),
                    number_format($remainingAmount, 2, '.', '')
                ),
            ])));

            if ($deal->payment_method === 'installment') {
                $deal->installments()
                    ->where('status', '!=', 'paid')
                    ->update([
                        'status' => 'cancelled',
                    ]);
            }

            $deal->payments()
                ->whereIn('status', ['pending', 'approved'])
                ->update([
                    'status' => 'cancelled',
                ]);

            $deal->update([
                'address_id' => $changeRequest->address_id,
                'discount_code_id' => $changeRequest->discount_code_id,
                'payment_method' => $changeRequest->payment_method,
                'installment_months' => $changeRequest->payment_method === 'installment'
                    ? $changeRequest->installment_months
                    : null,
                'base_amount' => $changeRequest->base_amount,
                'discount_amount' => $changeRequest->discount_amount,
                'final_amount' => $newTotal,
                'remaining_amount' => $remainingAmount,
                'user_note' => $changeRequest->user_note,
                'admin_note' => $adminNote,
            ]);

            if ($changeRequest->payment_method === 'installment') {
                $this->createInstallments(
                    $deal,
                    $remainingAmount,
                    (int) $changeRequest->installment_months
                );
            }

            $changeRequest->update([
                'status' => 'approved',
                'reviewed_at' => now(),
            ]);

            $changeRequest = $changeRequest->fresh([
                'deal.user',
                'user',
                'address',
                'discountCode',
            ]);

            DealRequestProcessed::dispatch(
                $changeRequest->deal,
                $changeRequest->user,
                'change_request',
                'approved'
            );

            return $changeRequest;
        });
    }

    public function rejectChangeRequest(
        DealChangeRequest $changeRequest,
        User $admin
    ): DealChangeRequest {
        return DB::transaction(function () use ($changeRequest, $admin) {
            $changeRequest = DealChangeRequest::query()
                ->lockForUpdate()
                ->findOrFail($changeRequest->id);

            if ($changeRequest->status !== 'pending') {
                throw ValidationException::withMessages([
                    'change_request' => 'This change request has already been reviewed.',
                ]);
            }

            $deal = Deal::query()
                ->lockForUpdate()
                ->findOrFail($changeRequest->deal_id);

            $changeRequest->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
            ]);

            $changeRequest = $changeRequest->fresh([
                'deal.user',
                'user',
                'address',
                'discountCode',
            ]);

            DealRequestProcessed::dispatch(
                $changeRequest->deal,
                $changeRequest->user,
                'change_request',
                'rejected'
            );

            return $changeRequest;
        });
    }

    private function createInstallments(
        Deal $deal,
        float $remainingAmount,
        int $months
    ): void {
        $totalCents = (int) round($remainingAmount * 100);

        $baseInstallmentCents = intdiv($totalCents, $months);
        $remainderCents = $totalCents % $months;

        for ($i = 1; $i <= $months; $i++) {
            $installmentCents = $baseInstallmentCents
                + ($i <= $remainderCents ? 1 : 0);

            $deal->installments()->create([
                'installment_number' => $i,
                'amount' => number_format(
                    $installmentCents / 100,
                    2,
                    '.',
                    ''
                ),
                'due_date' => now()
                    ->addMonthNoOverflow($i)
                    ->toDateString(),
                'status' => 'pending',
            ]);
        }
    }

    private function ensureDealStatus(
        Deal $deal,
        array $allowedStatuses
    ): void {
        if (! in_array($deal->status, $allowedStatuses, true)) {
            throw ValidationException::withMessages([
                'deal' => 'This deal cannot be changed in its current status.',
            ]);
        }
    }
}
