<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;

// LESSON: API routes live here. They are automatically prefixed with /api
// So Route::get('orders') becomes → GET /api/orders

// LESSON: apiResource() generates 5 routes automatically:
// GET    /api/orders           → index()
// POST   /api/orders           → store()
// GET    /api/orders/{order}   → show()
// PUT    /api/orders/{order}   → update()
// DELETE /api/orders/{order}   → destroy()
Route::apiResource('orders', OrderController::class);

// LESSON: Nested resource — items always live under a specific order
// GET    /api/orders/{order}/items                    → index()
// POST   /api/orders/{order}/items                    → store()
// PUT    /api/orders/{order}/items/{item}             → update()
// DELETE /api/orders/{order}/items/{item}             → destroy()
Route::apiResource('orders.items', OrderItemController::class)
    ->except(['show']); // we don't need a single item endpoint
