<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

// LESSON: This is the WEB controller — it returns Blade views, not JSON.
// It handles form submissions from the browser (create, update, delete).
// The API controller (Api/OrderController) handles JSON requests separately.

class OrderWebController extends Controller
{
    // Show all orders
    public function index()
    {
        $orders = Order::latest()->get();

        // LESSON: view() loads a Blade file and passes data to it.
        // 'orders.index' maps to resources/views/orders/index.blade.php
        return view('orders.index', compact('orders'));
    }

    // Show the create form
    public function create()
    {
        // No $order passed — form.blade.php will detect this and show "Create" mode
        return view('orders.form');
    }

    // Handle create form submission
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'status'        => 'required|in:pending,confirmed,cancelled',
        ]);

        $order = Order::create($data);

        // LESSON: redirect()->route() sends the user to a named route.
        // with('success') flashes a message to the session (shown once).
        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully!');
    }

    // Show one order with its items
    public function show(Order $order)
    {
        // Eager load items to avoid N+1 queries
        $order->load('items');

        return view('orders.show', compact('order'));
    }

    // Show the edit form
    public function edit(Order $order)
    {
        // $order is passed — form.blade.php will detect this and show "Edit" mode
        return view('orders.form', compact('order'));
    }

    // Handle edit form submission
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'status'        => 'required|in:pending,confirmed,cancelled',
        ]);

        $order->update($data);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully!');
    }

    // Delete an order (items deleted automatically via cascadeOnDelete)
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
