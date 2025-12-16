@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Payment Details - Order #{{ substr($order->id, 0, 8) }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Order Information -->
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Order Information</h3>
            </div>
            <div class="adomx-card-body">
                <table class="adomx-info-table">
                    <tr>
                        <td><strong>Order ID:</strong></td>
                        <td>#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Customer:</strong></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="{{ $order->user->profile_picture ? asset('storage/' . $order->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name) . '&background=4f46e5&color=fff' }}" 
                                     alt="{{ $order->user->name }}" 
                                     style="width: 40px; height: 40px; border-radius: 50%;">
                                <div>
                                    <div><strong>{{ $order->user->name }}</strong></div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $order->user->email }}</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Order Date:</strong></td>
                        <td>{{ $order->order_date->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Total Amount:</strong></td>
                        <td style="font-size: 24px; font-weight: bold; color: var(--primary-color);">${{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Order Items -->
        <div class="adomx-card" style="margin-top: 20px;">
            <div class="adomx-card-header">
                <h3>Order Items</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td><strong>{{ $item->course->title ?? 'N/A' }}</strong></td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                                <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details & Actions -->
    <div class="adomx-col-md-4">
        <!-- Transaction Info -->
        @if($order->transaction)
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Transaction Details</h3>
            </div>
            <div class="adomx-card-body">
                <table class="adomx-info-table">
                    <tr>
                        <td><strong>Transaction ID:</strong></td>
                        <td>{{ $order->transaction->transaction_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Method:</strong></td>
                        <td>{{ ucfirst($order->transaction->payment_method ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $order->transaction->status }}">
                                {{ ucfirst($order->transaction->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Processed At:</strong></td>
                        <td>{{ $order->transaction->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="adomx-card" style="margin-top: 20px;">
            <div class="adomx-card-header">
                <h3>Actions</h3>
            </div>
            <div class="adomx-card-body">
                @if($order->status == 'completed')
                    <form action="{{ route('admin.payments.process-refund', $order) }}" method="POST" style="margin-bottom: 15px;">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label class="adomx-label">Refund Amount</label>
                            <input type="number" name="amount" class="adomx-input" step="0.01" min="0" max="{{ $order->total_price }}" value="{{ $order->total_price }}">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label class="adomx-label">Reason <span class="adomx-required">*</span></label>
                            <textarea name="reason" class="adomx-input" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="adomx-btn adomx-btn-warning" style="width: 100%;">
                            <i class="fas fa-undo"></i> Process Refund
                        </button>
                    </form>
                @endif

                @if($order->status == 'pending')
                    <form action="{{ route('admin.payments.handle-dispute', $order) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label class="adomx-label">Resolution</label>
                            <select name="resolution" class="adomx-input" required>
                                <option value="refund">Full Refund</option>
                                <option value="partial_refund">Partial Refund</option>
                                <option value="reject">Reject</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label class="adomx-label">Amount (if partial)</label>
                            <input type="number" name="amount" class="adomx-input" step="0.01" min="0" max="{{ $order->total_price }}">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label class="adomx-label">Notes <span class="adomx-required">*</span></label>
                            <textarea name="notes" class="adomx-input" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="adomx-btn adomx-btn-primary" style="width: 100%;">
                            <i class="fas fa-gavel"></i> Handle Dispute
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.adomx-info-table {
    width: 100%;
}

.adomx-info-table td {
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
}

.adomx-info-table td:first-child {
    width: 40%;
    color: var(--text-secondary);
}
</style>
@endsection

