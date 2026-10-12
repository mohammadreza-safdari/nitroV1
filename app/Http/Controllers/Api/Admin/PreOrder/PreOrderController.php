<?php

namespace App\Http\Controllers\Api\Admin\PreOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PreOrder\ReviewPreOrderRequest;
use App\Models\Sales\PreOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class PreOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $preOrders = PreOrder::query()
            ->with([
                'user',
                'vehicle.vehicleModel.brand',
                'address',
            ])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->input('status')
                )
            )
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->input('search');

                    $query->where(function ($query) use ($search) {
                        $query->whereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                            ->orWhereHas('vehicle', function ($query) use ($search) {
                                $query->where(
                                    'stock_code',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                    });
                }
            )
            ->latest()
            ->paginate(20);

        return response()->json([
            'pre_orders' => $preOrders,
        ]);
    }

    public function approve(
        ReviewPreOrderRequest $request,
        PreOrder $preOrder
    ): JsonResponse {
        $preOrder = $this->review(
            $preOrder,
            'approved',
            $request->validated()
        );

        return response()->json([
            'message' => 'Pre-order approved successfully.',
            'pre_order' => $preOrder,
        ]);
    }

    public function reject(
        ReviewPreOrderRequest $request,
        PreOrder $preOrder
    ): JsonResponse {
        $preOrder = $this->review(
            $preOrder,
            'rejected',
            $request->validated()
        );

        return response()->json([
            'message' => 'Pre-order rejected successfully.',
            'pre_order' => $preOrder,
        ]);
    }

    private function review(
        PreOrder $preOrder,
        string $status,
        array $data
    ): PreOrder {
        return DB::transaction(function () use ($preOrder, $status, $data) {
            $preOrder = PreOrder::query()
                ->lockForUpdate()
                ->findOrFail($preOrder->id);

            if ($preOrder->status !== 'pending') {
                throw ValidationException::withMessages([
                    'pre_order' => 'Only pending pre-orders can be reviewed.',
                ]);
            }

            $preOrder->status = $status;

            if (array_key_exists('admin_note', $data)) {
                $preOrder->admin_note = $data['admin_note'];
            }

            $preOrder->save();

            return $preOrder->fresh([
                'user',
                'vehicle.vehicleModel.brand',
                'address',
            ]);
        });
    }
}
