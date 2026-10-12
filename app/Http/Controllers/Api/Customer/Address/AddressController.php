<?php

namespace App\Http\Controllers\Api\Customer\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Address\StoreAddressRequest;
use App\Http\Requests\Customer\Address\UpdateAddressRequest;
use App\Models\User\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $addresses = $request->user()
            ->addresses()
            ->latest()
            ->get();

        return response()->json([
            'addresses' => $addresses,
        ]);
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $address = $request->user()
            ->addresses()
            ->create($request->validated());

        return response()->json([
            'message' => 'Address created successfully.',
            'address' => $address,
        ], 201);
    }

    public function show(Address $address): JsonResponse
    {
        $this->authorize('view', $address);
        return response()->json([
            'address' => $address,
        ]);
    }

    public function update(
        UpdateAddressRequest $request,
        Address              $address
    ): JsonResponse
    {
        $this->authorize('update', $address);

        $address->update($request->validated());

        return response()->json([
            'message' => 'Address updated successfully.',
            'address' => $address->fresh(),
        ]);
    }

    public function destroy(Address $address): JsonResponse
    {
        $this->authorize('delete', $address);

        $address->delete();

        return response()->json([
            'message' => 'Address deleted successfully.',
        ]);
    }
}
