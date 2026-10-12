<?php

namespace App\Http\Controllers\Api\Admin\Discount;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Discount\StoreDiscountCodeRequest;
use App\Http\Requests\Admin\Discount\UpdateDiscountCodeRequest;
use App\Models\Sales\DiscountCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index(): JsonResponse
    {
        $discountCodes = DiscountCode::query()
            ->latest()
            ->paginate(20);

        return response()->json([
            'discount_codes' => $discountCodes,
        ]);
    }

    public function store(StoreDiscountCodeRequest $request): JsonResponse
    {
        $discountCode = DiscountCode::create($request->validated());

        return response()->json([
            'message' => 'Discount code created successfully.',
            'discount_code' => $discountCode,
        ], 201);
    }

    public function show(DiscountCode $discountCode): JsonResponse
    {
        return response()->json([
            'discount_code' => $discountCode,
        ]);
    }

    public function update(
        UpdateDiscountCodeRequest $request,
        DiscountCode              $discountCode
    ): JsonResponse
    {
        $discountCode->update($request->validated());

        return response()->json([
            'message' => 'Discount code updated successfully.',
            'discount_code' => $discountCode->fresh(),
        ]);
    }

    public function destroy(DiscountCode $discountCode): JsonResponse
    {
        $discountCode->delete();

        return response()->json([
            'message' => 'Discount code deleted successfully.',
        ]);
    }
}
