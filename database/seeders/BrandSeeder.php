<?php

namespace Database\Seeders;

use App\Models\Catalog\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Lamborghini',
            'Ferrari',
            'Bugatti',
            'Porsche',
            'McLaren',
            'Dodge',
            'Ford',
            'Chevrolet',
            'Aston Martin',
            'Mercedes-Benz',
            'BMW',
            'Ducati',
            'Yamaha',
            'Kawasaki',
            'Honda',
            'KTM',
            'Aprilia',
            'Triumph',
            'Harley-Davidson',
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'slug' => str()->slug($brand),
            ]);
        }
    }
}
