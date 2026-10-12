<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LocationController extends Controller
{
    public function provinces(): JsonResponse
    {
        $path = storage_path('app/locations/provinces.json');

        return response()->json([
            'provinces' => json_decode(File::get($path), true),
        ]);
    }

    public function cities(string $province): JsonResponse
    {
        $path = storage_path('app/locations/cities-filtered.json');

        $cities = collect(json_decode(File::get($path), true))
            ->where('province_id', (int)$province)
            ->values();

        return response()->json([
            'cities' => $cities,
        ]);
    }
}
