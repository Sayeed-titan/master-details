<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// LESSON: A Migration is how Laravel creates/modifies database tables.
// Each migration has an up() to apply and a down() to reverse it.

return new class extends Migration
{
    // up() runs when you do: php artisan migrate
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();                           // auto-increment primary key
            $table->string('customer_name');        // who placed the order
            $table->string('status')->default('pending'); // pending | confirmed | cancelled
            $table->decimal('total_amount', 10, 2)->default(0); // total price of all items
            $table->timestamps();                   // created_at & updated_at
        });
    }

    // down() runs when you do: php artisan migrate:rollback
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
