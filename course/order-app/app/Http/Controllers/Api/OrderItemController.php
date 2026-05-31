<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;
use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;

class OrderItemController extends Controller
{
    public function index(Order $order)
    {
        return OrderItemResource::collection($order->items);
    }

    public function store(StoreOrderItemRequest $request, Order $order)
    {
        $data             = $request->validated();
        $data['subtotal'] = $data['quantity'] * $data['unit_price'];

        $item = $order->items()->create($data);

        $this->recalculateTotal($order);

        return (new OrderItemResource($item))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateOrderItemRequest $request, Order $order, OrderItem $item)
    {
        $data = $request->validated();

        $quantity         = $data['quantity']   ?? $item->quantity;
        $unit_price       = $data['unit_price'] ?? $item->unit_price;
        $data['subtotal'] = $quantity * $unit_price;

        $item->update($data);

        $this->recalculateTotal($order);

        return new OrderItemResource($item);
    }

    public function destroy(Order $order, OrderItem $item)
    {
        $item->delete();

        $this->recalculateTotal($order);

        return response()->noContent();
    }

    private function recalculateTotal(Order $order): void
    {
        $order->update([
            'total_amount' => $order->items()->sum('subtotal'),
        ]);
    }
}
