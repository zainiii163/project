@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Payment Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payments.revenue-report') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-chart-line"></i>
            Revenue Report
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="adomx-row" style="margin-bottom: 20px;">
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Revenue</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">${{ number_format($stats['total_revenue'], 2) }}</div>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(79, 70, 229, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-dollar-sign" style="font-size: 20px; color: var(--primary-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Pending Orders</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">{{ $stats['pending_orders'] }}</div>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock" style="font-size: 20px; color: var(--warning-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Failed Transactions</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--danger-color);">{{ $stats['failed_transactions'] }}</div>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times-circle" style="font-size: 20px; color: var(--danger-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Today's Revenue</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">${{ number_format($stats['today_revenue'], 2) }}</div>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-day" style="font-size: 20px; color: var(--success-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Payments</h3>
    </div>
    
    <!-- Filters -->
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.payments.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="text" 
                   name="search" 
                   class="adomx-search-input" 
                   placeholder="Search by customer..." 
                   value="{{ request('search') }}"
                   style="flex: 1; max-width: 300px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            <input type="date" name="date_from" class="adomx-search-input" value="{{ request('date_from') }}" style="max-width: 150px;" placeholder="From Date">
            <input type="date" name="date_to" class="adomx-search-input" value="{{ request('date_to') }}" style="max-width: 150px;" placeholder="To Date">
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ substr($order->id, 0, 8) }}</strong></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="adomx-table-image">
                                    <img src="{{ $order->user->profile_picture ? asset('storage/' . $order->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name) . '&background=4f46e5&color=fff' }}" 
                                         alt="{{ $order->user->name }}">
                                </div>
                                <div>
                                    <div><strong>{{ $order->user->name }}</strong></div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $order->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $order->items->count() }} {{ $order->items->count() == 1 ? 'item' : 'items' }}</td>
                        <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                        <td>{{ $order->order_date->format('M d, Y H:i') }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.payments.show', $order) }}" class="adomx-action-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->status == 'completed')
                                    <form action="{{ route('admin.payments.process-refund', $order) }}" method="POST" style="display: inline;" onsubmit="return confirm('Process refund for this order?')">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Refund" style="color: var(--warning-color);">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="adomx-table-empty">No payments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $orders->links() }}
    </div>
</div>
@endsection

