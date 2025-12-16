@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Order Details #{{ substr($order->id, 0, 8) }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.orders.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary);">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card" style="margin-bottom: 25px;">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Order Information</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-info-grid">
            <div class="adomx-info-item">
                <h6 class="adomx-info-label">Customer Information</h6>
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
            </div>
            <div class="adomx-info-item">
                <h6 class="adomx-info-label">Order Information</h6>
                <p><strong>Order Date:</strong> {{ $order->order_date->format('M d, Y H:i') }}</p>
                <p><strong>Status:</strong> 
                    <span class="adomx-status-badge adomx-status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card" style="margin-bottom: 25px;">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Order Items</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td><strong>{{ $item->course->title ?? 'N/A' }}</strong></td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><strong>${{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align: right;">Total:</th>
                        <th style="font-size: 18px; color: var(--primary-color);">${{ number_format($order->total_price, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($order->transaction)
<div class="adomx-card" style="margin-bottom: 25px;">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Transaction Details</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-info-grid">
            <div class="adomx-info-item">
                <p><strong>Payment Method:</strong> {{ $order->transaction->payment_method }}</p>
                <p><strong>Amount:</strong> ${{ number_format($order->transaction->amount, 2) }}</p>
            </div>
            <div class="adomx-info-item">
                <p><strong>Status:</strong> 
                    <span class="adomx-status-badge adomx-status-{{ $order->transaction->status }}">
                        {{ ucfirst($order->transaction->status) }}
                    </span>
                </p>
                <p><strong>Transaction Date:</strong> {{ $order->transaction->transaction_date->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@can('update', $order)
<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Update Order Status</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="adomx-form-group" style="max-width: 300px;">
                <label class="adomx-form-label">Status</label>
                <select class="adomx-form-input" name="status" required>
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection
