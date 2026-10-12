<?php

namespace Database\Seeders;

use App\Models\Catalog\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $cars = Category::create([
            'name' => 'Cars',
            'slug' => 'cars',
            'parent_id' => null,
        ]);

        $motorcycles = Category::create([
            'name' => 'Motorcycles',
            'slug' => 'motorcycles',
            'parent_id' => null,
        ]);

        $carCategories = [
            ['name' => 'Sports Car', 'slug' => 'sports-car'],
            ['name' => 'Supercar', 'slug' => 'supercar'],
            ['name' => 'Hypercar', 'slug' => 'hypercar'],
            ['name' => 'Muscle Car', 'slug' => 'muscle-car'],
            ['name' => 'Luxury Car', 'slug' => 'luxury-car'],
            ['name' => 'Sedan', 'slug' => 'sedan'],
        ];

        foreach ($carCategories as $category) {
            Category::create([
                ...$category,
                'parent_id' => $cars->id,
            ]);
        }

        $motorcycleCategories = [
            ['name' => 'Sport Bike', 'slug' => 'sport-bike'],
            ['name' => 'Superbike', 'slug' => 'superbike'],
            ['name' => 'Naked Bike', 'slug' => 'naked-bike'],
            ['name' => 'Cruiser', 'slug' => 'cruiser'],
            ['name' => 'Adventure', 'slug' => 'adventure'],
            ['name' => 'Motocross', 'slug' => 'motocross'],
        ];

        foreach ($motorcycleCategories as $category) {
            Category::create([
                ...$category,
                'parent_id' => $motorcycles->id,
            ]);
        }
    }
}
