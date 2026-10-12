<?php

namespace Database\Seeders;

use App\Models\Catalog\CarDetail;
use App\Models\Catalog\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $details = [
            'Lamborghini Revuelto' => [
                'engine' => '6.5L V12 Hybrid',
                'horsepower' => 1001,
                'torque' => 725,
                'transmission' => '8-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'All-Wheel Drive',
                'acceleration' => '2.5 sec',
                'top_speed' => '350 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'electric_motors' => 3,
                    'battery' => '3.8 kWh',
                    'body_type' => 'Coupe',
                ],
            ],

            'Lamborghini Huracán' => [
                'engine' => '5.2L V10',
                'horsepower' => 631,
                'torque' => 600,
                'transmission' => '7-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'All-Wheel Drive',
                'acceleration' => '2.9 sec',
                'top_speed' => '325 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'body_type' => 'Coupe',
                    'engine_aspiration' => 'Naturally Aspirated',
                ],
            ],

            'Ferrari 296 GTB' => [
                'engine' => '3.0L V6 Twin-Turbo Hybrid',
                'horsepower' => 819,
                'torque' => 740,
                'transmission' => '8-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'Rear-Wheel Drive',
                'acceleration' => '2.9 sec',
                'top_speed' => '330 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'electric_motor' => true,
                    'body_type' => 'Coupe',
                ],
            ],

            'Ferrari SF90 Stradale' => [
                'engine' => '4.0L V8 Twin-Turbo Hybrid',
                'horsepower' => 986,
                'torque' => 800,
                'transmission' => '8-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'All-Wheel Drive',
                'acceleration' => '2.5 sec',
                'top_speed' => '340 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'electric_motors' => 3,
                    'body_type' => 'Coupe',
                ],
            ],

            'Bugatti Chiron' => [
                'engine' => '8.0L W16 Quad-Turbo',
                'horsepower' => 1479,
                'torque' => 1600,
                'transmission' => '7-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'All-Wheel Drive',
                'acceleration' => '2.4 sec',
                'top_speed' => '420 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'turbochargers' => 4,
                    'body_type' => 'Coupe',
                ],
            ],

            'Bugatti Tourbillon' => [
                'engine' => '8.3L V16 Hybrid',
                'horsepower' => 1775,
                'torque' => 900,
                'transmission' => '8-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'All-Wheel Drive',
                'acceleration' => '2.0 sec',
                'top_speed' => '445 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'electric_motors' => 3,
                    'body_type' => 'Coupe',
                ],
            ],

            'Porsche 911 Carrera' => [
                'engine' => '3.6L Flat-6 Turbo',
                'horsepower' => 388,
                'torque' => 450,
                'transmission' => '8-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'Rear-Wheel Drive',
                'acceleration' => '4.1 sec',
                'top_speed' => '294 km/h',
                'doors' => 2,
                'seats' => 4,
                'additional_specs' => [
                    'body_type' => 'Coupe',
                    'engine_layout' => 'Rear-Mounted',
                ],
            ],

            'Porsche 911 Turbo S' => [
                'engine' => '3.6L Flat-6 Twin-Turbo',
                'horsepower' => 701,
                'torque' => 800,
                'transmission' => '8-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'All-Wheel Drive',
                'acceleration' => '2.5 sec',
                'top_speed' => '322 km/h',
                'doors' => 2,
                'seats' => 4,
                'additional_specs' => [
                    'body_type' => 'Coupe',
                    'engine_layout' => 'Rear-Mounted',
                ],
            ],

            'McLaren 750S' => [
                'engine' => '4.0L V8 Twin-Turbo',
                'horsepower' => 740,
                'torque' => 800,
                'transmission' => '7-speed Dual-Clutch',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'Rear-Wheel Drive',
                'acceleration' => '2.8 sec',
                'top_speed' => '332 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'body_type' => 'Coupe',
                    'engine_layout' => 'Mid-Engine',
                ],
            ],

            'Dodge Challenger SRT Hellcat' => [
                'engine' => '6.2L Supercharged V8',
                'horsepower' => 717,
                'torque' => 889,
                'transmission' => '8-speed Automatic',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'Rear-Wheel Drive',
                'acceleration' => '3.6 sec',
                'top_speed' => '315 km/h',
                'doors' => 2,
                'seats' => 5,
                'additional_specs' => [
                    'supercharger' => true,
                    'body_type' => 'Coupe',
                ],
            ],

            'Ford Mustang Dark Horse' => [
                'engine' => '5.0L V8',
                'horsepower' => 500,
                'torque' => 567,
                'transmission' => '6-speed Manual',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'Rear-Wheel Drive',
                'acceleration' => '4.1 sec',
                'top_speed' => '250 km/h',
                'doors' => 2,
                'seats' => 4,
                'additional_specs' => [
                    'body_type' => 'Coupe',
                    'engine_aspiration' => 'Naturally Aspirated',
                ],
            ],

            'Chevrolet Camaro ZL1' => [
                'engine' => '6.2L Supercharged V8',
                'horsepower' => 650,
                'torque' => 881,
                'transmission' => '10-speed Automatic',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'Rear-Wheel Drive',
                'acceleration' => '3.5 sec',
                'top_speed' => '318 km/h',
                'doors' => 2,
                'seats' => 4,
                'additional_specs' => [
                    'supercharger' => true,
                    'body_type' => 'Coupe',
                ],
            ],

            'Aston Martin DB12' => [
                'engine' => '4.0L V8 Twin-Turbo',
                'horsepower' => 671,
                'torque' => 800,
                'transmission' => '8-speed Automatic',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'Rear-Wheel Drive',
                'acceleration' => '3.6 sec',
                'top_speed' => '325 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'body_type' => 'Grand Tourer',
                    'engine_layout' => 'Front-Mounted',
                ],
            ],

            'Mercedes-Benz AMG GT' => [
                'engine' => '4.0L V8 Twin-Turbo',
                'horsepower' => 577,
                'torque' => 800,
                'transmission' => '9-speed Automatic',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'All-Wheel Drive',
                'acceleration' => '3.2 sec',
                'top_speed' => '315 km/h',
                'doors' => 2,
                'seats' => 2,
                'additional_specs' => [
                    'body_type' => 'Coupe',
                    'engine_layout' => 'Front-Mounted',
                ],
            ],

            'BMW M8 Competition' => [
                'engine' => '4.4L V8 Twin-Turbo',
                'horsepower' => 617,
                'torque' => 750,
                'transmission' => '8-speed Automatic',
                'fuel_type' => 'Gasoline',
                'drive_type' => 'All-Wheel Drive',
                'acceleration' => '3.0 sec',
                'top_speed' => '305 km/h',
                'doors' => 2,
                'seats' => 4,
                'additional_specs' => [
                    'body_type' => 'Coupe',
                    'engine_layout' => 'Front-Mounted',
                ],
            ],
        ];

        foreach ($details as $modelName => $detail) {
            $vehicle = Vehicle::whereHas('vehicleModel', function ($query) use ($modelName) {
                $query->where('slug', str()->slug($modelName));
            })->firstOrFail();

            CarDetail::create([
                'vehicle_id' => $vehicle->id,
                ...$detail,
            ]);
        }
    }
}
