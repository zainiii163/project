@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Payouts</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.payments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Payments
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Payout History</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Commissions</th>
                        <th>Processed At</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $payout)
                        <tr>
                            <td>
                                <strong style="color: var(--primary-color); font-size: 18px;">${{ number_format($payout->amount, 2) }}</strong>
                            </td>
                            <td>
                                <span style="text-transform: capitalize;">
                                    {{ str_replace('_', ' ', $payout->payment_method) }}
                                </span>
                            </td>
                            <td>
                                <span class="adomx-status-badge adomx-status-{{ $payout->status }}">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="adomx-badge">{{ $payout->commissions->count() }} commissions</span>
                            </td>
                            <td>
                                @if($payout->processed_at)
                                    {{ $payout->processed_at->format('M d, Y H:i') }}
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td>{{ $payout->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('teacher.payments.show-payout', $payout) }}" class="adomx-btn adomx-btn-sm adomx-btn-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="adomx-table-empty">No payouts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
            {{ $payouts->links() }}
        </div>
    </div>
</div>
@endsection

