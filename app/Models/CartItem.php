<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'menu_item_id',
        'quantity',
        'price_at_time',
        'special_note',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_time' => 'integer',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function getSubtotalAttribute(): int
    {
        return $this->price_at_time * $this->quantity;
    }
}