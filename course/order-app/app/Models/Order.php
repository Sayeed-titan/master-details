<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// LESSON: A Model represents a database table.
// The Order model maps to the 'orders' table automatically (Laravel convention).

class Order extends Model
{
    // LESSON: $fillable lists columns that are allowed to be mass-assigned.
    // Mass assignment = creating/updating a record using an array e.g. Order::create([...])
    // Without $fillable, Laravel will throw a MassAssignmentException for security.
    protected $fillable = [
        'customer_name',
        'status',
        'total_amount',
    ];

    // LESSON: hasMany() defines the ONE side of a One-to-Many relationship.
    // One Order HAS MANY OrderItems.
    // Laravel automatically looks for 'order_id' in the order_items table.
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
