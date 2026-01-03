@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Payments & Earnings</h2>
    </div>
</div>

<!-- Statistics Cards -->
<div class="adomx-row" style="margin-bottom: 20px;">
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Earnings</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">${{ number_format($stats['total_earnings'], 2) }}</div>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Pending Earnings</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">${{ number_format($stats['pending_earnings'], 2) }}</div>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Payouts</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">${{ number_format($stats['total_payouts'], 2) }}</div>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="font-size: 20px; color: var(--success-color);"></i>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Pending Payouts</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">${{ number_format($stats['pending_payouts'], 2) }}</div>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-hourglass-half" style="font-size: 20px; color: var(--info-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="adomx-tabs">
    <button class="adomx-tab-btn active" onclick="showTab('commissions')">Commissions</button>
    <button class="adomx-tab-btn" onclick="showTab('payouts')">Payouts</button>
</div>

<!-- Commissions Tab -->
<div id="commissions" class="adomx-tab-content active">
    <div class="adomx-table-card">
        <div class="adomx-table-header">
            <h3 class="adomx-table-title">My Commissions</h3>
        </div>
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissions as $commission)
                        <tr>
                            <td>{{ $commission->course->title ?? 'N/A' }}</td>
                            <td>{{ $commission->order->user->name ?? 'N/A' }}</td>
                            <td>${{ number_format($commission->amount, 2) }}</td>
                            <td>
                                <span class="adomx-status-badge adomx-status-{{ $commission->status }}">
                                    {{ ucfirst($commission->status) }}
                                </span>
                            </td>
                            <td>{{ $commission->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="adomx-table-empty">No commissions found</td>
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

<!-- Payouts Tab -->
<div id="payouts" class="adomx-tab-content">
    <div class="adomx-table-card">
        <div class="adomx-table-header">
            <h3 class="adomx-table-title">My Payouts</h3>
        </div>
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $payout)
                        <tr>
                            <td>${{ number_format($payout->amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payout->payment_method)) }}</td>
                            <td>
                                <span class="adomx-status-badge adomx-status-{{ $payout->status }}">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </td>
                            <td>{{ $payout->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('teacher.payments.show-payout', $payout) }}" class="adomx-btn adomx-btn-sm adomx-btn-primary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="adomx-table-empty">No payouts found</td>
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

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.adomx-tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.adomx-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>
@endsection

