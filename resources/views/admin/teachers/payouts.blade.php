@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Teacher Payouts - {{ $teacher->name }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.teachers.show', $teacher) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Earnings Summary -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Earnings Summary</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Total Revenue</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">${{ number_format($earnings['total_revenue'], 2) }}</div>
                    </div>
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Teacher Earnings</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">${{ number_format($earnings['teacher_earnings'], 2) }}</div>
                    </div>
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Platform Commission</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">${{ number_format($earnings['platform_commission'], 2) }}</div>
                    </div>
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Pending Payouts</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">${{ number_format($earnings['pending_payouts'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Payout -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Process Payout</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('admin.teachers.manage-payout', $teacher) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 20px;">
                        <label class="adomx-label">Amount</label>
                        <input type="number" 
                               name="amount" 
                               class="adomx-input" 
                               step="0.01" 
                               min="0" 
                               max="{{ $earnings['pending_payouts'] }}" 
                               value="{{ $earnings['pending_payouts'] }}" 
                               required>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="adomx-label">Notes</label>
                        <textarea name="notes" 
                                  class="adomx-input" 
                                  rows="4" 
                                  placeholder="Add any notes about this payout..."></textarea>
                    </div>
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-dollar-sign"></i> Process Payout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payout History -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Payout History</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Payout history will be displayed here -->
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px; color: var(--text-secondary);">
                                    No payout history available
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

