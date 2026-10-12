<?php

namespace App\Services\Catalog;

use App\Models\Catalog\Vehicle;
use App\Models\Catalog\VehicleImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VehicleImageService
{
    public function store(
        Vehicle $vehicle,
        UploadedFile $image,
        bool $isPrimary,
        int $sortOrder
    ): VehicleImage {
        $path = $image->store('vehicles', 'public');

        /** @var VehicleImage $vehicleImage */
        $vehicleImage = $vehicle->images()->create([
            'path' => $path,
            'is_primary' => false,
            'sort_order' => $sortOrder,
        ]);

        if ($isPrimary) {
            $this->setPrimary($vehicle, $vehicleImage);
        }

        return $vehicleImage;
    }

    public function update(
        VehicleImage $vehicleImage,
        ?UploadedFile $image,
        bool $isPrimary,
        int $sortOrder
    ): VehicleImage {
        $data = [
            'is_primary' => false,
            'sort_order' => $sortOrder,
        ];

        if ($image) {
            // delete previous image
            Storage::disk('public')->delete($vehicleImage->path);
            // store new one
            $data['path'] = $image->store('vehicles', 'public');
        }

        $vehicleImage->update($data);

        if ($isPrimary) {
            $this->setPrimary($vehicleImage->vehicle, $vehicleImage);
        }

        return $vehicleImage->fresh();
    }

    public function delete(VehicleImage $vehicleImage): void
    {
        Storage::disk('public')->delete($vehicleImage->path);

        $vehicleImage->delete();
    }

    private function setPrimary(
        Vehicle $vehicle,
        VehicleImage $image
    ): void {
        $vehicle->images()
            ->whereKeyNot($image->id)
            ->update([
                'is_primary' => false,
            ]);

        $image->update([
            'is_primary' => true,
        ]);
    }
}
