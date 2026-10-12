<?php

namespace Database\Seeders;

use App\Models\Catalog\MotorcycleDetail;
use App\Models\Catalog\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotorcycleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $details = [
            'Ducati Panigale V4' => [
                'engine' => '1,103cc V4',
                'horsepower' => 215,
                'torque' => 123,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Liquid Cooled',
                'seat_height' => 850,
                'wheelbase' => 1_469,
                'fuel_capacity' => 17,
                'weight' => 195,
                'top_speed' => '300+ km/h',
                'additional_specs' => [
                    'engine_type' => 'V4',
                    'riding_position' => 'Supersport',
                ],
            ],

            'Ducati Streetfighter V4' => [
                'engine' => '1,103cc V4',
                'horsepower' => 208,
                'torque' => 123,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Liquid Cooled',
                'seat_height' => 845,
                'wheelbase' => 1_488,
                'fuel_capacity' => 16,
                'weight' => 197,
                'top_speed' => '270+ km/h',
                'additional_specs' => [
                    'engine_type' => 'V4',
                    'riding_position' => 'Naked',
                ],
            ],

            'Yamaha YZF-R1' => [
                'engine' => '998cc Inline-4',
                'horsepower' => 200,
                'torque' => 113,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Liquid Cooled',
                'seat_height' => 860,
                'wheelbase' => 1_405,
                'fuel_capacity' => 17,
                'weight' => 201,
                'top_speed' => '299 km/h',
                'additional_specs' => [
                    'engine_type' => 'Inline-4',
                    'riding_position' => 'Supersport',
                ],
            ],

            'Kawasaki Ninja ZX-10R' => [
                'engine' => '998cc Inline-4',
                'horsepower' => 203,
                'torque' => 114,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Liquid Cooled',
                'seat_height' => 835,
                'wheelbase' => 1_450,
                'fuel_capacity' => 17,
                'weight' => 207,
                'top_speed' => '299 km/h',
                'additional_specs' => [
                    'engine_type' => 'Inline-4',
                    'riding_position' => 'Supersport',
                ],
            ],

            'Honda CBR1000RR-R' => [
                'engine' => '999cc Inline-4',
                'horsepower' => 215,
                'torque' => 113,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Liquid Cooled',
                'seat_height' => 830,
                'wheelbase' => 1_455,
                'fuel_capacity' => 16.1,
                'weight' => 201,
                'top_speed' => '300+ km/h',
                'additional_specs' => [
                    'engine_type' => 'Inline-4',
                    'riding_position' => 'Supersport',
                ],
            ],

            'KTM 1290 Super Duke R' => [
                'engine' => '1,301cc V-Twin',
                'horsepower' => 180,
                'torque' => 140,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Liquid Cooled',
                'seat_height' => 835,
                'wheelbase' => 1_491,
                'fuel_capacity' => 16,
                'weight' => 189,
                'top_speed' => '280+ km/h',
                'additional_specs' => [
                    'engine_type' => 'V-Twin',
                    'riding_position' => 'Naked',
                ],
            ],

            'Aprilia RSV4' => [
                'engine' => '1,099cc V4',
                'horsepower' => 217,
                'torque' => 125,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Liquid Cooled',
                'seat_height' => 840,
                'wheelbase' => 1_435,
                'fuel_capacity' => 18,
                'weight' => 202,
                'top_speed' => '300+ km/h',
                'additional_specs' => [
                    'engine_type' => 'V4',
                    'riding_position' => 'Supersport',
                ],
            ],

            'Triumph Street Triple 765' => [
                'engine' => '765cc Inline-3',
                'horsepower' => 128,
                'torque' => 80,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Liquid Cooled',
                'seat_height' => 836,
                'wheelbase' => 1_402,
                'fuel_capacity' => 15,
                'weight' => 188,
                'top_speed' => '225 km/h',
                'additional_specs' => [
                    'engine_type' => 'Inline-3',
                    'riding_position' => 'Naked',
                ],
            ],

            'Harley-Davidson Fat Boy 114' => [
                'engine' => '1,868cc V-Twin',
                'horsepower' => 94,
                'torque' => 155,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'cooling_system' => 'Air/Oil Cooled',
                'seat_height' => 675,
                'wheelbase' => 1_665,
                'fuel_capacity' => 18.9,
                'weight' => 317,
                'top_speed' => '175 km/h',
                'additional_specs' => [
                    'engine_type' => 'V-Twin',
                    'riding_position' => 'Cruiser',
                ],
            ],
        ];
        /*
                $details = [
                    'Yamaha YZF-R1' => [
                        'engine' => '998cc Inline-4',
                        'horsepower' => 200,
                    ],
                ];
                --->
                $modelName : Yamaha YZF-R1
                $detail :
                [
                    'engine' => '998cc Inline-4',
                    'horsepower' => 200,
                ]
         */
        foreach ($details as $modelName => $detail) {
            $vehicle = Vehicle::whereHas('vehicleModel', function ($query) use ($modelName) {
                $query->where('slug', str()->slug($modelName));
            })->firstOrFail();

            MotorcycleDetail::create([
                'vehicle_id' => $vehicle->id,
                ...$detail,
            ]);
        }
    }
}
