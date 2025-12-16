@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Orders Management</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Recent Transaction</h3>
        <i class="fas fa-cog"></i>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="adomx-checkbox">
                    </th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <input type="checkbox" class="adomx-checkbox">
                        </td>
                        <td>
                            <strong>#{{ substr($order->id, 0, 8) }}</strong>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="adomx-table-image">
                                    <img src="{{ $order->user->profile_picture ? asset('storage/' . $order->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name) . '&background=4f46e5&color=fff' }}" 
                                         alt="{{ $order->user->name }}">
                                </div>
                                <span>{{ $order->user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $order->order_date->format('M d, Y') }}</td>
                        <td>{{ $order->items->count() }} {{ $order->items->count() == 1 ? 'item' : 'items' }}</td>
                        <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="adomx-action-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="adomx-table-empty">No orders found</td>
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
