<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorcycleDetail extends Model
{
    protected $fillable = [
        'vehicle_id',
        'engine',
        'horsepower',
        'torque',
        'transmission',
        'fuel_type',
        'cooling_system',
        'seat_height',
        'wheelbase',
        'fuel_capacity',
        'weight',
        'top_speed',
        'additional_specs',
    ];

    protected $casts = [
        'additional_specs' => 'array',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
