@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')

{{-- Order Header (Master) --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span>Order #{{ $order->id }} — {{ $order->customer_name }}</span>
        <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-light">Edit Order</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <small class="text-muted">Status</small>
                @php
                    $badge = match($order->status) {
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default     => 'warning',
                    };
                @endphp
                <p><span class="badge bg-{{ $badge }} fs-6">{{ ucfirst($order->status) }}</span></p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Total Amount</small>
                <p class="fw-bold fs-5">${{ number_format($order->total_amount, 2) }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Date</small>
                <p>{{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Order Items (Details) --}}
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Order Items</span>
        {{-- This button will be wired to JS in Phase 4 --}}
        <button class="btn btn-sm btn-success" id="addItemBtn">+ Add Item</button>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="itemsTableBody">
                @forelse($order->items as $item)
                <tr id="item-row-{{ $item->id }}">
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->subtotal, 2) }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary edit-item-btn"
                                data-id="{{ $item->id }}"
                                data-product="{{ $item->product_name }}"
                                data-qty="{{ $item->quantity }}"
                                data-price="{{ $item->unit_price }}">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-item-btn"
                                data-id="{{ $item->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="5" class="text-center text-muted py-3">No items yet. Add one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add / Edit Item Modal --}}
{{-- LESSON: A Bootstrap modal is a popup form. We'll use JS in Phase 4 to open it. --}}
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalTitle">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="itemId">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" id="productName" class="form-control" placeholder="e.g. Laptop">
                </div>
                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" id="quantity" class="form-control" min="1" value="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Unit Price ($)</label>
                    <input type="number" id="unitPrice" class="form-control" min="0" step="0.01" value="0.00">
                </div>
                <div id="itemModalError" class="text-danger d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveItemBtn">Save Item</button>
            </div>
        </div>
    </div>
</div>

{{-- Pass order id to JS --}}
<script>
    const ORDER_ID = {{ $order->id }};
</script>

@endsection

@section('scripts')
    {{-- Phase 4: JS will go in a separate file and be loaded here --}}
@endsection
