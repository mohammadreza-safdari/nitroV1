<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_model_id',
        'stock_code',
        'year',
        'color',
        'mileage',
        'price',
        'status',
        'condition',
        'description',
    ];

    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sort_order');
    }

    public function carDetail(): HasOne
    {
        return $this->hasOne(CarDetail::class);
    }

    public function motorcycleDetail(): HasOne
    {
        return $this->hasOne(MotorcycleDetail::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function hasActivePreOrderOffer(): bool
    {
        return $this->offers()
            ->where('type', 'pre_order')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->exists();
    }
}
