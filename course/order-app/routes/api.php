<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;

// LESSON: API routes are prefixed with /api automatically by Laravel.
// We also add a name prefix 'api.' so these route names don't clash
// with the web routes (which also use 'orders.*' names).
//
// API route names:  api.orders.index, api.orders.store, etc.
// Web route names:  orders.index, orders.store, etc.

Route::name('api.')->group(function () {

    // GET    /api/orders           → index()
    // POST   /api/orders           → store()
    // GET    /api/orders/{order}   → show()
    // PUT    /api/orders/{order}   → update()
    // DELETE /api/orders/{order}   → destroy()
    Route::apiResource('orders', OrderController::class);

    // Nested: items always live under a specific order
    // GET    /api/orders/{order}/items                → index()
    // POST   /api/orders/{order}/items                → store()
    // PUT    /api/orders/{order}/items/{item}         → update()
    // DELETE /api/orders/{order}/items/{item}         → destroy()
    Route::apiResource('orders.items', OrderItemController::class)
        ->except(['show']);
});
