<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomRequest extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'request_details',
        'occasion',
        'quantity_estimate',
        'budget',
        'preferred_date',
        'attachments',
        'status',
        'admin_note',
        'quoted_amount',
        'admin_response',
    ];

    protected $casts = [
        'attachments' => 'array',
        'budget' => 'integer',
        'quoted_amount' => 'integer',
        'preferred_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}