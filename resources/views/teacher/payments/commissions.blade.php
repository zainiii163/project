@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Commissions</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.payments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Payments
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Commission History</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Student</th>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Commission Rate</th>
                        <th>Status</th>
                        <th>Paid At</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissions as $commission)
                        <tr>
                            <td>
                                <div style="font-weight: 500;">{{ $commission->course->title ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $commission->order->user->name ?? 'N/A' }}</td>
                            <td>
                                <code style="padding: 5px 10px; background: var(--bg-light); border-radius: 4px; font-size: 12px;">
                                    #{{ substr($commission->order_id, 0, 8) }}
                                </code>
                            </td>
                            <td>
                                <strong style="color: var(--primary-color);">${{ number_format($commission->amount, 2) }}</strong>
                            </td>
                            <td>{{ number_format($commission->commission_rate, 2) }}%</td>
                            <td>
                                <span class="adomx-status-badge adomx-status-{{ $commission->status }}">
                                    {{ ucfirst($commission->status) }}
                                </span>
                            </td>
                            <td>
                                @if($commission->paid_at)
                                    {{ $commission->paid_at->format('M d, Y') }}
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td>{{ $commission->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="adomx-table-empty">No commissions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
            {{ $commissions->links() }}
        </div>
    </div>
</div>
@endsection

