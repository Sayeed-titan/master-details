<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// LESSON: The OrderItem model maps to the 'order_items' table.
// This is the DETAILS side — each item belongs to one Order.

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    // LESSON: belongsTo() defines the MANY side of a One-to-Many relationship.
    // Each OrderItem BELONGS TO one Order.
    // Laravel uses 'order_id' column on this table to find the parent Order.
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
