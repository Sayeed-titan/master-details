{{-- LESSON: @extends tells Blade to wrap this view inside the layout file.
     Everything inside @section('content') replaces @yield('content') in the layout. --}}
@extends('layouts.app')

@section('title', 'All Orders')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">All Orders</h4>
    <a href="{{ route('orders.create') }}" class="btn btn-success btn-sm">+ New Order</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- LESSON: @forelse is like foreach but has an @empty fallback
                     when the collection has no items --}}
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>
                        {{-- LESSON: We use a match expression to assign Bootstrap badge colors --}}
                        @php
                            $badge = match($order->status) {
                                'confirmed'  => 'success',
                                'cancelled'  => 'danger',
                                default      => 'warning',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        {{-- View button --}}
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>

                        {{-- Edit button --}}
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

                        {{-- Delete button — uses a form because DELETE needs a form/POST --}}
                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this order and all its items?')">
                            @csrf
                            {{-- LESSON: @method('DELETE') spoofs the DELETE HTTP verb
                                 because HTML forms only support GET and POST --}}
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
