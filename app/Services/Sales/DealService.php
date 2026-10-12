<?php

namespace App\Services\Sales;

use App\Models\Catalog\Vehicle;
use App\Models\Sales\Deal;
use App\Models\Sales\DealChangeRequest;
use App\Models\Sales\DiscountCode;
use App\Models\Sales\Installment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DealService
{
    public function create(
        int $userId,
        Vehicle $vehicle,
        array $data,
        array $pricing,
        ?DiscountCode $discountCode
    ): array {
        return DB::transaction(function () use (
            $userId,
            $vehicle,
            $data,
            $pricing,
            $discountCode
        ) {
            $lockedVehicle = Vehicle::query()
                ->whereKey($vehicle->id)
                ->lockForUpdate()
                ->firstOrFail();

            $existingDeal = Deal::query()
                ->where('user_id', $userId)
                ->where('vehicle_id', $lockedVehicle->id)
                ->whereIn('status', ['pending', 'approved'])
                ->lockForUpdate()
                ->first();

            if ($existingDeal) {
                return [
                    'created' => false,
                    'deal' => $existingDeal->load([
                        'vehicle.vehicleModel.brand',
                        'vehicle.images',
                        'address',
                        'discountCode',
                    ]),
                ];
            }

            $paymentMethod = $data['payment_method'];

            $discountAmount =
                (float) $pricing['offer_discount']
                + (float) $pricing['discount_code_discount'];

            $deal = Deal::create([
                'user_id' => $userId,
                'vehicle_id' => $lockedVehicle->id,
                'type' => $data['type'],
                'status' => 'pending',
                'quantity' => 1,
                'payment_method' => $paymentMethod,
                'installment_months' => $paymentMethod === 'installment'
                    ? (int) $data['installment_months']
                    : null,
                'base_amount' => $pricing['base_price'],
                'discount_amount' => $discountAmount,
                'final_amount' => $pricing['final_price'],
                'discount_code_id' => $discountCode?->id,
                'address_id' => $data['address_id'],
                'user_note' => $data['user_note'] ?? null,
            ]);

            if ($paymentMethod === 'installment') {
                $this->createInstallments($deal);
            }

            return [
                'created' => true,
                'deal' => $deal->fresh([
                    'vehicle.vehicleModel.brand',
                    'vehicle.images',
                    'address',
                    'discountCode',
                    'installments',
                ]),
            ];
        });
    }

    public function requestUpdate(
        Deal $deal,
        int $userId,
        array $data,
        array $pricing,
        ?DiscountCode $discountCode
    ): DealChangeRequest {
        return DB::transaction(function () use (
            $deal,
            $userId,
            $data,
            $pricing,
            $discountCode
        ) {
            $lockedDeal = Deal::query()
                ->whereKey($deal->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedDeal->user_id !== $userId) {
                throw ValidationException::withMessages([
                    'deal' => 'You cannot change another customer\'s deal.',
                ]);
            }

            if (! in_array(
                $lockedDeal->status,
                ['pending', 'approved'],
                true
            )) {
                throw ValidationException::withMessages([
                    'deal' => 'This deal cannot be changed in its current status.',
                ]);
            }

            $existingRequest = DealChangeRequest::query()
                ->where('deal_id', $lockedDeal->id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if ($existingRequest) {
                throw ValidationException::withMessages([
                    'deal' => 'A change request is already awaiting admin review.',
                ]);
            }

            $paymentMethod = $data['payment_method'];

            $installmentMonths = $paymentMethod === 'installment'
                ? (int) $data['installment_months']
                : null;

            $discountAmount =
                (float) $pricing['offer_discount']
                + (float) $pricing['discount_code_discount'];

            $hasChanged =
                $lockedDeal->payment_method !== $paymentMethod
                || (int) $lockedDeal->address_id !== (int) $data['address_id']
                || $lockedDeal->discount_code_id !== $discountCode?->id
                || $lockedDeal->installment_months !== $installmentMonths
                || $this->toCents($lockedDeal->base_amount)
                !== $this->toCents($pricing['base_price'])
                || $this->toCents($lockedDeal->discount_amount)
                !== $this->toCents($discountAmount)
                || $this->toCents($lockedDeal->final_amount)
                !== $this->toCents($pricing['final_price']);

            if (! $hasChanged) {
                throw ValidationException::withMessages([
                    'deal' => 'No changes were detected in the purchase terms.',
                ]);
            }

            return DealChangeRequest::create([
                'deal_id' => $lockedDeal->id,
                'user_id' => $userId,
                'address_id' => $data['address_id'],
                'discount_code_id' => $discountCode?->id,
                'payment_method' => $paymentMethod,
                'installment_months' => $installmentMonths,
                'base_amount' => $pricing['base_price'],
                'discount_amount' => $discountAmount,
                'final_amount' => $pricing['final_price'],
                'user_note' => $data['user_note'] ?? null,
                'status' => 'pending',
            ])->load([
                'deal',
                'address',
                'discountCode',
            ]);
        });
    }

    private function createInstallments(Deal $deal): void
    {
        $months = (int) $deal->installment_months;

        if ($months < 1) {
            return;
        }

        $totalAmount = (float) $deal->final_amount;

        $baseAmount = round($totalAmount / $months, 2);

        $createdAmount = 0;

        for ($i = 1; $i <= $months; $i++) {
            $amount = $i === $months
                ? round($totalAmount - $createdAmount, 2)
                : $baseAmount;

            Installment::create([
                'deal_id' => $deal->id,
                'installment_number' => $i,
                'amount' => $amount,
                'due_date' => now()
                    ->addMonths($i)
                    ->toDateString(),
                'status' => 'pending',
                'payment_id' => null,
            ]);

            $createdAmount += $amount;
        }
    }

    private function toCents(int|float|string|null $amount): int
    {
        return (int) round((float) $amount * 100);
    }
}
