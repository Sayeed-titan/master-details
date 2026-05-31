<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// LESSON: OrderResource wraps the Order model.
// Notice how we nest OrderItemResource inside — this is how master-details
// looks in a JSON API response: one order with its items embedded.

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'customer_name' => $this->customer_name,
            'status'        => $this->status,
            'total_amount'  => $this->total_amount,
            'created_at'    => $this->created_at->toDateTimeString(),

            // LESSON: whenLoaded() only includes 'items' if we eager loaded them.
            // This prevents N+1 query problems — we only load items when needed.
            'items'         => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
