@extends('layouts.app')

{{-- LESSON: We reuse this same form for both Create and Edit.
     $order is null when creating, and an existing Order model when editing.
     isset($order) lets us switch between the two modes. --}}

@section('title', isset($order) ? 'Edit Order' : 'New Order')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                {{ isset($order) ? 'Edit Order #' . $order->id : 'Create New Order' }}
            </div>
            <div class="card-body">

                {{-- LESSON: When editing, we use PUT method via @method('PUT') spoof.
                     When creating, it's a plain POST. --}}
                <form action="{{ isset($order) ? route('orders.update', $order) : route('orders.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($order)) @method('PUT') @endif

                    {{-- Customer Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Customer Name</label>
                        {{-- LESSON: old('customer_name') repopulates the field if validation fails.
                             $order->customer_name fills it when editing. --}}
                        <input type="text" name="customer_name"
                               value="{{ old('customer_name', $order->customer_name ?? '') }}"
                               class="form-control @error('customer_name') is-invalid @enderror"
                               placeholder="Enter customer name">
                        {{-- LESSON: @error shows the validation error message for a field --}}
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            @foreach(['pending', 'confirmed', 'cancelled'] as $status)
                                <option value="{{ $status }}"
                                    {{ old('status', $order->status ?? 'pending') === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            {{ isset($order) ? 'Update Order' : 'Create Order' }}
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection
