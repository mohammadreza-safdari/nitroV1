<?php

namespace App\Models\Sales;

use App\Models\User;
use App\Models\User\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealChangeRequest extends Model
{
    protected $fillable = [
        'deal_id',
        'user_id',
        'address_id',
        'discount_code_id',
        'payment_method',
        'installment_months',
        'base_amount',
        'discount_amount',
        'final_amount',
        'user_note',
        'admin_note',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }
}
