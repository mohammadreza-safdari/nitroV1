<?php

namespace App\Services\Sales;

use App\Models\Catalog\Vehicle;
use App\Models\Sales\DiscountCode;
use Illuminate\Validation\ValidationException;

class PricingService
{
    public function calculate(
        Vehicle $vehicle,
        ?string $discountCode = null
    ): array {

        if ($vehicle->hasActivePreOrderOffer()) {
            throw ValidationException::withMessages([
                'vehicle' => 'This vehicle is currently available for pre-order only.',
            ]);
        }

        $now = now();

        $basePrice = (float) $vehicle->price;

        $offerDiscount = 0;
        $offer = null;

        $activeOffers = $vehicle->offers()
            ->where('is_active', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            })
            ->get();

        $discountOffer = $activeOffers
            ->where('type', 'discount')
            ->first();

        if ($discountOffer) {
            $offer = $discountOffer;

            $offerDiscount = $this->calculateDiscount(
                $basePrice,
                $discountOffer->discount_type,
                (float) $discountOffer->discount_value
            );
        }

        $priceAfterOffer = max(
            0,
            $basePrice - $offerDiscount
        );

        $discountCodeDiscount = 0;
        $appliedDiscountCode = null;

        if ($discountCode) {
            $discountCodeModel = $this->findValidDiscountCode(
                $discountCode,
                $now
            );

            $discountCodeDiscount = $this->calculateDiscount(
                $priceAfterOffer,
                $discountCodeModel->type,
                (float) $discountCodeModel->value
            );

            $appliedDiscountCode = $discountCodeModel->code;
        }

        $finalPrice = max(
            0,
            $priceAfterOffer - $discountCodeDiscount
        );

        return [
            'base_price' => $basePrice,
            'offer_discount' => $offerDiscount,
            'price_after_offer' => $priceAfterOffer,
            'discount_code' => $appliedDiscountCode,
            'discount_code_discount' => $discountCodeDiscount,
            'final_price' => $finalPrice,
        ];
    }

    private function calculateDiscount(
        float $price,
        string $type,
        float $value
    ): float {
        return match ($type) {
            'percentage' => min(
                $price,
                $price * ($value / 100)
            ),

            'fixed' => min(
                $price,
                $value
            ),

            default => throw ValidationException::withMessages([
                'discount' => 'Invalid discount type.',
            ]),
        };
    }

    private function findValidDiscountCode(
        string $code,
               $now
    ): DiscountCode {
        $discountCode = DiscountCode::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        if (! $discountCode) {
            throw ValidationException::withMessages([
                'discount_code' => 'The discount code is invalid or inactive.',
            ]);
        }

        if (
            $discountCode->starts_at &&
            $discountCode->starts_at->isAfter($now)
        ) {
            throw ValidationException::withMessages([
                'discount_code' => 'The discount code is not active yet.',
            ]);
        }

        if (
            $discountCode->expires_at &&
            $discountCode->expires_at->isBefore($now)
        ) {
            throw ValidationException::withMessages([
                'discount_code' => 'The discount code has expired.',
            ]);
        }

        if (
            $discountCode->max_uses !== null &&
            $discountCode->used_count >= $discountCode->max_uses
        ) {
            throw ValidationException::withMessages([
                'discount_code' => 'The discount code usage limit has been reached.',
            ]);
        }

        return $discountCode;
    }
}
