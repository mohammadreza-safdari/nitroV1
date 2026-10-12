<?php

namespace App\Models\Sales;

use App\Models\Catalog\Vehicle;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'status',
        'reserved_until',
        'deposit_amount',
        'notes',
    ];

    protected $casts = [
        'reserved_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
