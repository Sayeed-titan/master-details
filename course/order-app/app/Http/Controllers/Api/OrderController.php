<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

// LESSON: This is an API Controller — it returns JSON, not views.
// Each method maps to an HTTP verb + URL (defined in routes/api.php).
//
// GET    /api/orders         → index()   — list all orders
// POST   /api/orders         → store()   — create a new order
// GET    /api/orders/{id}    → show()    — get one order with its items
// PUT    /api/orders/{id}    → update()  — update an order
// DELETE /api/orders/{id}    → destroy() — delete an order

class OrderController extends Controller
{
    // List all orders (without items for performance)
    public function index()
    {
        // LESSON: latest() orders by created_at DESC — newest first
        $orders = Order::latest()->get();

        // LESSON: ResourceCollection wraps each order through OrderResource
        return OrderResource::collection($orders);
    }

    // Create a new order (without items — items are added separately)
    public function store(Request $request)
    {
        // LESSON: validate() checks the incoming request data.
        // If validation fails, Laravel automatically returns a 422 JSON error.
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'status'        => 'in:pending,confirmed,cancelled',
        ]);

        $order = Order::create($data);

        // LESSON: 201 = HTTP Created status code
        return new OrderResource($order);
    }

    // Get one order WITH its items (eager loaded)
    public function show(Order $order)
    {
        // LESSON: Route Model Binding — Laravel automatically finds the Order
        // by the {id} in the URL and injects it. No need for Order::find($id).
        // load('items') eager loads the relationship to avoid N+1 queries.
        $order->load('items');

        return new OrderResource($order);
    }

    // Update an existing order
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'customer_name' => 'sometimes|string|max:255',
            'status'        => 'sometimes|in:pending,confirmed,cancelled',
        ]);

        $order->update($data);

        return new OrderResource($order);
    }

    // Delete an order (items are deleted automatically via cascadeOnDelete)
    public function destroy(Order $order)
    {
        $order->delete();

        // LESSON: 204 = No Content — success but nothing to return
        return response()->noContent();
    }
}
