<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

// LESSON: Notice how clean the controller is now.
// All validation logic lives in the Form Request classes.
// The controller only handles the business logic.

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Order::latest()->get());
    }

    // LESSON: Type-hinting StoreOrderRequest instead of Request
    // makes Laravel automatically validate before this method runs.
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated());

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Order $order)
    {
        return new OrderResource($order->load('items'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->noContent();
    }
}
