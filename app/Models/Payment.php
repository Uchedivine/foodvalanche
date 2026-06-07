<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'gateway',
        'gateway_reference',
        'gateway_response',
        'amount',
        'expected_amount',
        'currency',
        'status',
        'initiated_at',
        'paid_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'amount' => 'integer',
        'expected_amount' => 'integer',
        'initiated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}