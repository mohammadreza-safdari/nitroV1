<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Catalog\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Catalog\Brand\UpdateBrandRequest;
use App\Models\Catalog\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{

    public function index(): JsonResponse
    {
        $brands = Brand::query()
            ->orderBy('name')
            ->get();

        return response()->json([
            'brands' => $brands,
        ]);
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {

        $brand = Brand::create($request->validated());

        return response()->json([
            'message' => 'Brand created successfully.',
            'brand' => $brand,
        ], 201);
    }

    public function show(Brand $brand): JsonResponse
    {
        $brand->load('vehicleModels');

        return response()->json([
            'brand' => $brand,
        ]);
    }

    public function update(
        UpdateBrandRequest $request,
        Brand              $brand
    ): JsonResponse
    {
        $brand->update($request->validated());

        return response()->json([
            'message' => 'Brand updated successfully.',
            'brand' => $brand->fresh(),
        ]);
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully.',
        ]);
    }
}

