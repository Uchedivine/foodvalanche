<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'guest_name',
        'guest_phone',
        'guest_email',
        'status',
        'order_type',
        'table_identifier',
        'payment_method',
        'payment_status',
        'subtotal',
        'discount_amount',
        'delivery_fee',
        'total',
        'coupon_id',
        'delivery_address_id',
        'notes',
        'estimated_ready_at',
        'requires_verification',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'discount_amount' => 'integer',
        'delivery_fee' => 'integer',
        'total' => 'integer',
        'requires_verification' => 'boolean',
        'estimated_ready_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function deliveryAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'delivery_address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}