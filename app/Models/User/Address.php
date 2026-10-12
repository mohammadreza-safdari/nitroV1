<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'recipient_name',
        'phone',
        'province',
        'city',
        'address',
        'postal_code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
