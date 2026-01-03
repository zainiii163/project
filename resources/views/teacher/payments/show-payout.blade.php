@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Payout Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.payments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Payout Information</h3>
            </div>
            <div class="adomx-card-body">
                <table class="adomx-info-table">
                    <tr>
                        <td><strong>Amount:</strong></td>
                        <td>${{ number_format($payout->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Method:</strong></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payout->payment_method)) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $payout->status }}">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $payout->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @if($payout->processed_at)
                    <tr>
                        <td><strong>Processed:</strong></td>
                        <td>{{ $payout->processed_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($payout->notes)
                    <tr>
                        <td><strong>Notes:</strong></td>
                        <td>{{ $payout->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="adomx-card" style="margin-top: 20px;">
            <div class="adomx-card-header">
                <h3>Commissions Included</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payout->commissions as $commission)
                                <tr>
                                    <td>{{ $commission->course->title ?? 'N/A' }}</td>
                                    <td>{{ $commission->order->user->name ?? 'N/A' }}</td>
                                    <td>${{ number_format($commission->amount, 2) }}</td>
                                    <td>{{ $commission->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="adomx-table-empty">No commissions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

