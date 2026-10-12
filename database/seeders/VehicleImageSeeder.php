<?php

namespace Database\Seeders;

use App\Models\Catalog\Vehicle;
use App\Models\Catalog\VehicleImage;
use Illuminate\Database\Seeder;

class VehicleImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            'LAM-REV-001' => 'images/car/lamborghini-revuelto.jpg',
            'LAM-HUR-001' => 'images/car/lamborghini-huracan.jpeg',
            'FER-296-001' => 'images/car/ferrari-296-gtb.jpg',
            'FER-SF9-001' => 'images/car/ferrari-sf90-stradale.jpg',
            'BUG-CHI-001' => 'images/car/bugatti-chiron.jpg',
            'BUG-TOU-001' => 'images/car/bugatti-tourbillon.jpg',
            'POR-911-001' => 'images/car/porsche-911-carrera.jpg',
            'POR-TUR-001' => 'images/car/porsche-911-turbo-s.jpg',
            'MCL-750-001' => 'images/car/mclaren-750s.jpg',
            'DOD-HEL-001' => 'images/car/dodge-challenger-srt-hellcat.jpg',
            'FOR-DKH-001' => 'images/car/ford-mustang-dark-horse.jpg',
            'CHE-ZL1-001' => 'images/car/chevrolet-camaro-zl1.jpg',
            'AST-DB12-001' => 'images/car/aston-martin-db12.jpg',
            'MER-AMG-001' => 'images/car/mercedes-benz-amg-gt.jpg',
            'BMW-M8-001' => 'images/car/bmw-m8-competition.jpg',

            'DUC-PV4-001' => 'images/motorcycle/ducati-panigale-v4.jpg',
            'DUC-STV-001' => 'images/motorcycle/ducati-streetfighter-v4.jpg',
            'YAM-R1-001' => 'images/motorcycle/yamaha-yzf-r1.jpg',
            'KAW-ZX10-001' => 'images/motorcycle/kawasaki-ninja-zx-10r.jpg',
            'HON-CBR-001' => 'images/motorcycle/honda-cbr1000rr-r.jpg',
            'KTM-SDR-001' => 'images/motorcycle/ktm-1290-super-duke-r.jpg',
            'APR-RSV4-001' => 'images/motorcycle/aprilia-rsv4.jpg',
            'TRI-ST765-001' => 'images/motorcycle/triumph-street-triple-765.jpg',
            'HD-FAT-001' => 'images/motorcycle/harley-davidson-fat-boy-114.jpg',
        ];

        foreach ($images as $stockCode => $path) {
            $vehicle = Vehicle::where('stock_code', $stockCode)->firstOrFail();

            VehicleImage::create([
                'vehicle_id' => $vehicle->id,
                'path' => $path,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }
    }
}
