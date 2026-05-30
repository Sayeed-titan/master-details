<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// LESSON: This is the DETAILS table.
// It has a foreign key (order_id) that links each item back to its parent Order.
// One Order → Many Order Items (hasMany / belongsTo relationship)

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // FOREIGN KEY: links this item to a specific order
            // constrained() automatically references the 'orders' table id
            // cascadeOnDelete() means: if the order is deleted, its items are deleted too
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->string('product_name');         // name of the product
            $table->integer('quantity');            // how many units
            $table->decimal('unit_price', 10, 2);  // price per unit
            $table->decimal('subtotal', 10, 2);    // quantity * unit_price (calculated)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
