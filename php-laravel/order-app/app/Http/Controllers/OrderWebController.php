<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

// LESSON: This is the WEB controller — it returns Blade views, not JSON.
// It now uses Form Request classes for validation — no inline validate() calls needed.

class OrderWebController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.form');
    }

    // LESSON: StoreOrderRequest handles validation automatically before this runs.
    // $request->validated() returns only the fields that passed validation.
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated());

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully!');
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.form', compact('order'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully!');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
