<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
    ];

    public function vehicleModels(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
