@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Invoices</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Completed Orders - Invoices</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->order_date->format('M d, Y') }}</td>
                        <td>
                            @foreach($order->items as $item)
                                <div>{{ $item->course->title ?? 'N/A' }}</div>
                            @endforeach
                        </td>
                        <td>
                            <strong>${{ number_format($order->total_price, 2) }}</strong>
                            @if($order->discount_amount)
                                <br><small style="color: var(--text-secondary);">
                                    Subtotal: ${{ number_format($order->total_price + $order->discount_amount, 2) }}
                                </small>
                                <br><small style="color: var(--success-color);">
                                    Discount: -${{ number_format($order->discount_amount, 2) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            {{ $order->transaction->payment_method ?? 'N/A' }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('student.payments.invoice', $order) }}" class="adomx-action-btn" title="View Invoice">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('student.payments.download-invoice', $order) }}" class="adomx-action-btn" title="Download PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No invoices available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

