<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderWebController;

// LESSON: Web routes return HTML views (Blade).
// resource() generates all 7 routes: index, create, store, show, edit, update, destroy
// These are named routes — e.g. route('orders.index'), route('orders.show', $order)

Route::resource('orders', OrderWebController::class);

// Redirect root URL to orders list
Route::get('/', fn() => redirect()->route('orders.index'));
