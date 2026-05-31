<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// LESSON: An API Resource transforms a Model into a JSON response.
// $this->resource refers to the OrderItem model instance.
// We control exactly which fields go out — no accidental data leaks.

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'product_name' => $this->product_name,
            'quantity'     => $this->quantity,
            'unit_price'   => $this->unit_price,
            'subtotal'     => $this->subtotal,
        ];
    }
}
