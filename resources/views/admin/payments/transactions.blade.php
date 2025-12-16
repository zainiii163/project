@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Transaction History</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Payments
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Transactions</h3>
    </div>
    
    <!-- Filters -->
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.payments.transactions') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            <select name="payment_method" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Methods</option>
                <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                <option value="paypal" {{ request('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="stripe" {{ request('payment_method') == 'stripe' ? 'selected' : '' }}>Stripe</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td><strong>{{ $transaction->transaction_id ?? 'N/A' }}</strong></td>
                        <td>#{{ substr($transaction->order->id ?? '', 0, 8) }}</td>
                        <td>{{ $transaction->order->user->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'N/A')) }}</td>
                        <td><strong>${{ number_format($transaction->amount ?? 0, 2) }}</strong></td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $transaction->status }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.payments.show', $transaction->order) }}" class="adomx-action-btn" title="View Order">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="adomx-table-empty">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $transactions->links() }}
    </div>
</div>
@endsection

