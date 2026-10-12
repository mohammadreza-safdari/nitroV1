<?php

namespace Database\Seeders;

use App\Models\Catalog\Brand;
use App\Models\Catalog\Category;
use App\Models\Catalog\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = Category::whereIn('slug', [
            'sports-car',
            'supercar',
            'hypercar',
            'muscle-car',
            'luxury-car',
            'sedan',
            'sport-bike',
            'superbike',
            'naked-bike',
            'cruiser',
            'adventure',
            'motocross',
        ])->get()->keyBy('slug');

        $models = [
            ['brand' => 'Lamborghini', 'category' => 'supercar', 'name' => 'Revuelto'],
            ['brand' => 'Lamborghini', 'category' => 'supercar', 'name' => 'Huracán'],
            ['brand' => 'Ferrari', 'category' => 'supercar', 'name' => '296 GTB'],
            ['brand' => 'Ferrari', 'category' => 'supercar', 'name' => 'SF90 Stradale'],
            ['brand' => 'Bugatti', 'category' => 'hypercar', 'name' => 'Chiron'],
            ['brand' => 'Bugatti', 'category' => 'hypercar', 'name' => 'Tourbillon'],
            ['brand' => 'Porsche', 'category' => 'sports-car', 'name' => '911 Carrera'],
            ['brand' => 'Porsche', 'category' => 'sports-car', 'name' => '911 Turbo S'],
            ['brand' => 'McLaren', 'category' => 'supercar', 'name' => '750S'],
            ['brand' => 'Dodge', 'category' => 'muscle-car', 'name' => 'Challenger SRT Hellcat'],
            ['brand' => 'Ford', 'category' => 'muscle-car', 'name' => 'Mustang Dark Horse'],
            ['brand' => 'Chevrolet', 'category' => 'muscle-car', 'name' => 'Camaro ZL1'],
            ['brand' => 'Aston Martin', 'category' => 'luxury-car', 'name' => 'DB12'],
            ['brand' => 'Mercedes-Benz', 'category' => 'luxury-car', 'name' => 'AMG GT'],
            ['brand' => 'BMW', 'category' => 'luxury-car', 'name' => 'M8 Competition'],

            ['brand' => 'Ducati', 'category' => 'superbike', 'name' => 'Panigale V4'],
            ['brand' => 'Ducati', 'category' => 'naked-bike', 'name' => 'Streetfighter V4'],
            ['brand' => 'Yamaha', 'category' => 'superbike', 'name' => 'YZF-R1'],
            ['brand' => 'Kawasaki', 'category' => 'superbike', 'name' => 'Ninja ZX-10R'],
            ['brand' => 'Honda', 'category' => 'sport-bike', 'name' => 'CBR1000RR-R'],
            ['brand' => 'KTM', 'category' => 'naked-bike', 'name' => '1290 Super Duke R'],
            ['brand' => 'Aprilia', 'category' => 'superbike', 'name' => 'RSV4'],
            ['brand' => 'Triumph', 'category' => 'naked-bike', 'name' => 'Street Triple 765'],
            ['brand' => 'Harley-Davidson', 'category' => 'cruiser', 'name' => 'Fat Boy 114'],
        ];

        foreach ($models as $model) {
            $brand = Brand::where('name', $model['brand'])->firstOrFail();

            VehicleModel::create([
                'brand_id' => $brand->id,
                'category_id' => $categories[$model['category']]->id,
                'name' => $model['name'],
                'slug' => str()->slug($model['brand'] . '-' . $model['name']),
            ]);
        }
    }
}
