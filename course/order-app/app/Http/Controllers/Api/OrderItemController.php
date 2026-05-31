<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;

// LESSON: This controller manages Order Items under a specific Order.
// The routes are nested — notice Order is always in the URL:
//
// GET    /api/orders/{order}/items         → index()   — list items of an order
// POST   /api/orders/{order}/items         → store()   — add item to an order
// PUT    /api/orders/{order}/items/{item}  → update()  — update an item
// DELETE /api/orders/{order}/items/{item}  → destroy() — remove an item

class OrderItemController extends Controller
{
    // List all items for a specific order
    public function index(Order $order)
    {
        // LESSON: We access items through the relationship, not a raw query.
        // This ensures we only get items belonging to THIS order.
        return OrderItemResource::collection($order->items);
    }

    // Add a new item to an order
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity'     => 'required|integer|min:1',
            'unit_price'   => 'required|numeric|min:0',
        ]);

        // Calculate subtotal
        $data['subtotal'] = $data['quantity'] * $data['unit_price'];

        // LESSON: Creating through the relationship auto-sets order_id
        $item = $order->items()->create($data);

        // Recalculate and update the order's total_amount
        $this->recalculateTotal($order);

        return new OrderItemResource($item);
    }

    // Update an existing item
    public function update(Request $request, Order $order, OrderItem $item)
    {
        $data = $request->validate([
            'product_name' => 'sometimes|string|max:255',
            'quantity'     => 'sometimes|integer|min:1',
            'unit_price'   => 'sometimes|numeric|min:0',
        ]);

        // Recalculate subtotal if quantity or price changed
        $quantity   = $data['quantity']   ?? $item->quantity;
        $unit_price = $data['unit_price'] ?? $item->unit_price;
        $data['subtotal'] = $quantity * $unit_price;

        $item->update($data);

        $this->+($order);

        return new OrderItemResource($item);
    }

    // Remove an item from an order
    public function destroy(Order $order, OrderItem $item)
    {
        $item->delete();

        $this->recalculateTotal($order);

        return response()->noContent();
    }

    // LESSON: Private helper — recalculates order total from its current items.
    // Called after every add/update/delete of an item.
    private function recalculateTotal(Order $order): void
    {
        $order->update([
            'total_amount' => $order->items()->sum('subtotal'),
        ]);
    }
}
