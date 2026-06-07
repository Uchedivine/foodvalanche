<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryZone extends Model
{
    protected $fillable = [
        'name',
        'areas',
        'delivery_fee',
        'estimated_minutes',
        'is_active',
    ];

    protected $casts = [
        'areas' => 'array',
        'delivery_fee' => 'integer',
        'estimated_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function customerAddresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }
}