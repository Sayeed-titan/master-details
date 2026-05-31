<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;

// LESSON: A Seeder inserts sample/test data into the database.
// Run it with: php artisan db:seed
// Or fresh migrate + seed: php artisan migrate:fresh --seed

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // LESSON: We define sample orders as an array.
        // Each order has a 'items' key with its child order items.
        $orders = [
            [
                'customer_name' => 'Alice Johnson',
                'status'        => 'confirmed',
                'items' => [
                    ['product_name' => 'Laptop',  'quantity' => 1, 'unit_price' => 850.00],
                    ['product_name' => 'Mouse',   'quantity' => 2, 'unit_price' => 25.00],
                    ['product_name' => 'Keyboard','quantity' => 1, 'unit_price' => 45.00],
                ],
            ],
            [
                'customer_name' => 'Bob Smith',
                'status'        => 'pending',
                'items' => [
                    ['product_name' => 'Monitor', 'quantity' => 2, 'unit_price' => 300.00],
                    ['product_name' => 'HDMI Cable','quantity' => 3, 'unit_price' => 10.00],
                ],
            ],
            [
                'customer_name' => 'Carol White',
                'status'        => 'cancelled',
                'items' => [
                    ['product_name' => 'Headphones', 'quantity' => 1, 'unit_price' => 120.00],
                    ['product_name' => 'USB Hub',    'quantity' => 1, 'unit_price' => 35.00],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            // LESSON: We separate the items array from the order fields
            $items = $orderData['items'];
            unset($orderData['items']);

            // Calculate total_amount by summing up all item subtotals
            $total = collect($items)->sum(fn($item) => $item['quantity'] * $item['unit_price']);

            // LESSON: Order::create() inserts a new row into the orders table
            // and returns the created Order model instance with its new id.
            $order = Order::create([
                ...$orderData,
                'total_amount' => $total,
            ]);

            // LESSON: Now insert each item linked to this order via order_id
            foreach ($items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];

                // LESSON: $order->items()->create() automatically sets order_id
                // because we're creating through the relationship
                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'subtotal'     => $subtotal,
                ]);
            }
        }
    }
}
