<?php

namespace App\Models\Sales;

use App\Models\Catalog\Vehicle;
use App\Models\User;
use App\Models\User\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOrder extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'address_id',
        'deposit',
        'status',
        'user_note',
        'admin_note',
    ];

    protected $casts = [
        'deposit' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
