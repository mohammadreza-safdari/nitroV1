<?php

namespace Database\Seeders;

use App\Models\Catalog\Offer;
use App\Models\Catalog\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = [
            [
                'stock_code' => 'LAM-HUR-001',
                'title' => 'Lamborghini Huracán Special Offer',
                'description' => 'A limited special offer on the Lamborghini Huracán.',
                'type' => 'discount',
                'discount_type' => 'percentage',
                'discount_value' => 8,
                'starts_at' => now(),
                'ends_at' => now()->addDays(14),
                'is_active' => true,
            ],
            [
                'stock_code' => 'FER-296-001',
                'title' => 'Ferrari 296 GTB Exclusive Offer',
                'description' => 'Exclusive pricing available for a limited period.',
                'type' => 'limited_time',
                'discount_type' => 'fixed',
                'discount_value' => 15000,
                'starts_at' => now(),
                'ends_at' => now()->addDays(10),
                'is_active' => true,
            ],
            [
                'stock_code' => 'BUG-CHI-001',
                'title' => 'Bugatti Chiron Featured',
                'description' => 'A featured hypercar currently highlighted by Nitro.',
                'type' => 'featured',
                'discount_type' => null,
                'discount_value' => null,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'stock_code' => 'POR-TUR-001',
                'title' => 'Porsche 911 Turbo S Reservation Offer',
                'description' => 'Reserve the Porsche 911 Turbo S with a special reservation offer.',
                'type' => 'reservation',
                'discount_type' => 'fixed',
                'discount_value' => 5000,
                'starts_at' => now(),
                'ends_at' => now()->addDays(21),
                'is_active' => true,
            ],
            [
                'stock_code' => 'DOD-HEL-001',
                'title' => 'Hellcat Performance Deal',
                'description' => 'Special pricing on the Dodge Challenger SRT Hellcat.',
                'type' => 'discount',
                'discount_type' => 'percentage',
                'discount_value' => 5,
                'starts_at' => now(),
                'ends_at' => now()->addDays(14),
                'is_active' => true,
            ],
            [
                'stock_code' => 'FOR-DKH-001',
                'title' => 'Mustang Dark Horse Featured',
                'description' => 'A featured American muscle car available through Nitro.',
                'type' => 'featured',
                'discount_type' => null,
                'discount_value' => null,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'stock_code' => 'DUC-PV4-001',
                'title' => 'Panigale V4 Pre-order Offer',
                'description' => 'Special pre-order opportunity for the Ducati Panigale V4.',
                'type' => 'pre_order',
                'discount_type' => 'fixed',
                'discount_value' => 1000,
                'starts_at' => now(),
                'ends_at' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'stock_code' => 'YAM-R1-001',
                'title' => 'Yamaha YZF-R1 Limited Offer',
                'description' => 'Limited-time offer for the Yamaha YZF-R1.',
                'type' => 'limited_time',
                'discount_type' => 'percentage',
                'discount_value' => 6,
                'starts_at' => now(),
                'ends_at' => now()->addDays(7),
                'is_active' => true,
            ],
        ];

        foreach ($offers as $offer) {
            $vehicle = Vehicle::where('stock_code', $offer['stock_code'])
                ->firstOrFail();

            Offer::create([
                'vehicle_id' => $vehicle->id,
                'title' => $offer['title'],
                'description' => $offer['description'],
                'type' => $offer['type'],
                'discount_type' => $offer['discount_type'],
                'discount_value' => $offer['discount_value'],
                'starts_at' => $offer['starts_at'],
                'ends_at' => $offer['ends_at'],
                'is_active' => $offer['is_active'],
            ]);
        }
    }
}
