<?php

namespace App\Models\Sales;

use App\Models\Catalog\Vehicle;
use App\Models\User;
use App\Models\User\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'type',
        'status',
        'quantity',
        'base_amount',
        'discount_amount',
        'final_amount',
        'discount_code_id',
        'address_id',
        'admin_note',
        'user_note',
        'approved_at',
        'finalized_at',
        'cancelled_at',
        'installment_months',
        'payment_method',
        'remaining_amount',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'finalized_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'remaining_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function changeRequests(): HasMany
    {
        return $this->hasMany(DealChangeRequest::class);
    }
}
