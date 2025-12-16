@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Transaction History</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Transactions</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->order_date->format('M d, Y H:i') }}</td>
                        <td>
                            @foreach($order->items as $item)
                                <div>{{ $item->course->title ?? 'N/A' }}</div>
                            @endforeach
                        </td>
                        <td>
                            <strong>${{ number_format($order->total_price, 2) }}</strong>
                            @if($order->discount_amount)
                                <br><small style="color: var(--success-color);">
                                    Discount: -${{ number_format($order->discount_amount, 2) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($order->status === 'completed')
                                <span class="adomx-status-badge adomx-status-published">Completed</span>
                            @elseif($order->status === 'pending')
                                <span class="adomx-status-badge adomx-status-draft">Pending</span>
                            @elseif($order->status === 'failed')
                                <span class="adomx-status-badge" style="background: var(--danger-color);">Failed</span>
                            @else
                                <span class="adomx-status-badge">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('student.payments.invoice', $order) }}" class="adomx-action-btn" title="View Invoice">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                @if($order->status === 'completed')
                                    <a href="{{ route('student.payments.download-invoice', $order) }}" class="adomx-action-btn" title="Download Invoice">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $orders->links() }}
    </div>
</div>
@endsection

