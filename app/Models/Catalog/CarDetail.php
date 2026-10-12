<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarDetail extends Model
{
    protected $fillable = [
        'vehicle_id',
        'engine',
        'horsepower',
        'torque',
        'transmission',
        'fuel_type',
        'drive_type',
        'acceleration',
        'top_speed',
        'doors',
        'seats',
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
