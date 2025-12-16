@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Payment History - {{ $student->name }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.students.show', $student) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Student
        </a>
    </div>
</div>

<!-- Summary -->
<div class="adomx-card" style="margin-bottom: 20px;">
    <div class="adomx-card-header">
        <h3>Payment Summary</h3>
    </div>
    <div class="adomx-card-body">
        <div style="text-align: center; padding: 20px;">
            <div style="font-size: 36px; font-weight: bold; color: var(--primary-color); margin-bottom: 10px;">
                ${{ number_format($totalSpent, 2) }}
            </div>
            <div style="font-size: 14px; color: var(--text-secondary);">
                Total Amount Spent
            </div>
        </div>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Payment History</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Courses</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td><strong>#{{ substr($payment->id, 0, 8) }}</strong></td>
                        <td>{{ $payment->order_date->format('M d, Y H:i') }}</td>
                        <td>
                            @foreach($payment->items as $item)
                                <div>{{ $item->course->title ?? 'N/A' }}</div>
                            @endforeach
                        </td>
                        <td><strong>${{ number_format($payment->total_price, 2) }}</strong></td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $payment->status }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment) }}" class="adomx-action-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No payments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

