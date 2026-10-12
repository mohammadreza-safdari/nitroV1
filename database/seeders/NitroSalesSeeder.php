<?php

namespace Database\Seeders;

use App\Models\Sales\Deal;
use App\Models\Sales\Installment;
use App\Models\Sales\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NitroSalesSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $userId = 1;
            $addressId = 1;

            $this->clearExistingDeals($userId);

            $vehicle4 = $this->getVehicle(4);
            $vehicle5 = $this->getVehicle(5);
            $vehicle6 = $this->getVehicle(6);
            $vehicle7 = $this->getVehicle(7);

            $deal1 = Deal::create([
                'user_id' => $userId,
                'vehicle_id' => $vehicle4->id,
                'type' => 'purchase',
                'status' => 'approved',
                'quantity' => 1,
                'base_amount' => $vehicle4->price,
                'discount_amount' => 0,
                'final_amount' => $vehicle4->price,
                'address_id' => $addressId,
                'approved_at' => Carbon::now()->subDays(10),
                'payment_method' => 'full',
                'installment_months' => null,
                'admin_note' => 'Purchase approved by Nitro admin.',
            ]);

            Payment::create([
                'deal_id' => $deal1->id,
                'amount' => $deal1->final_amount,
                'method' => 'bank_transfer',
                'status' => 'paid',
                'transaction_reference' => 'NITRO-FULL-0001',
                'paid_at' => Carbon::now()->subDays(8),
            ]);

            $deal2 = Deal::create([
                'user_id' => $userId,
                'vehicle_id' => $vehicle5->id,
                'type' => 'purchase',
                'status' => 'approved',
                'quantity' => 1,
                'base_amount' => $vehicle5->price,
                'discount_amount' => 0,
                'final_amount' => $vehicle5->price,
                'address_id' => $addressId,
                'approved_at' => Carbon::now()->subDays(20),
                'payment_method' => 'installment',
                'installment_months' => 12,
                'admin_note' => 'Installment purchase approved by Nitro admin.',
            ]);

            $monthlyAmount = round(
                $deal2->final_amount / 12,
                2
            );

            $remainingAmount = $deal2->final_amount;

            $installments = [];

            for ($i = 1; $i <= 12; $i++) {
                $amount = $i === 12
                    ? $remainingAmount
                    : $monthlyAmount;

                $remainingAmount -= $amount;

                $installments[] = Installment::create([
                    'deal_id' => $deal2->id,
                    'installment_number' => $i,
                    'amount' => $amount,
                    'due_date' => Carbon::now()
                        ->subMonths(12 - $i)
                        ->startOfDay(),
                    'paid_at' => $i <= 4
                        ? Carbon::now()->subDays(30 * (5 - $i))
                        : null,
                    'status' => $i <= 4
                        ? 'paid'
                        : 'pending',
                    'payment_id' => null,
                ]);
            }

            $paidInstallments = collect($installments)
                ->take(4);

            $paidAmount = $paidInstallments->sum('amount');

            $installmentPayment = Payment::create([
                'deal_id' => $deal2->id,
                'amount' => $paidAmount,
                'method' => 'bank_transfer',
                'status' => 'paid',
                'transaction_reference' => 'NITRO-INSTALLMENT-0001',
                'paid_at' => Carbon::now()->subDays(5),
            ]);

            $paidInstallments->each(function (
                Installment $installment
            ) use ($installmentPayment) {
                $installment->update([
                    'payment_id' => $installmentPayment->id,
                ]);
            });

            /*
             * Deal #3
             * Vehicle 6
             * Rejected
             */
            Deal::create([
                'user_id' => $userId,
                'vehicle_id' => $vehicle6->id,
                'type' => 'purchase',
                'status' => 'rejected',
                'quantity' => 1,
                'base_amount' => $vehicle6->price,
                'discount_amount' => 0,
                'final_amount' => $vehicle6->price,
                'address_id' => $addressId,
                'approved_at' => null,
                'payment_method' => 'full',
                'installment_months' => null,
                'admin_note' => 'Purchase request rejected by Nitro admin.',
                'user_note' => 'Customer requested full payment.',
            ]);

            /*
             * Deal #4
             * Vehicle 7
             * Rejected
             */
            Deal::create([
                'user_id' => $userId,
                'vehicle_id' => $vehicle7->id,
                'type' => 'purchase',
                'status' => 'rejected',
                'quantity' => 1,
                'base_amount' => $vehicle7->price,
                'discount_amount' => 0,
                'final_amount' => $vehicle7->price,
                'address_id' => $addressId,
                'approved_at' => null,
                'payment_method' => 'installment',
                'installment_months' => 24,
                'admin_note' => 'Installment purchase request rejected by Nitro admin.',
                'user_note' => 'Customer requested 24-month installment plan.',
            ]);
        });
    }

    private function getVehicle(int $id)
    {
        return \App\Models\Catalog\Vehicle::query()
            ->findOrFail($id);
    }

    private function clearExistingDeals(int $userId): void
    {
        $dealIds = Deal::query()
            ->where('user_id', $userId)
            ->whereIn('vehicle_id', [4, 5, 6, 7])
            ->pluck('id');

        if ($dealIds->isEmpty()) {
            return;
        }

        Payment::query()
            ->whereIn('deal_id', $dealIds)
            ->delete();

        Installment::query()
            ->whereIn('deal_id', $dealIds)
            ->delete();

        Deal::query()
            ->whereIn('id', $dealIds)
            ->delete();
    }
}
