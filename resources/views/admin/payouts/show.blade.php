@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Payout Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payouts.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-8">
        <div class="adomx-card mb-4">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Payout Information</h3>
            </div>
            <div class="adomx-card-body">
                <div class="mb-3">
                    <strong>Teacher:</strong> {{ $payout->teacher->name }}
                </div>
                <div class="mb-3">
                    <strong>Amount:</strong> <span style="font-size: 24px; color: var(--primary-color);">${{ number_format($payout->amount, 2) }}</span>
                </div>
                <div class="mb-3">
                    <strong>Payment Method:</strong> {{ $payout->payment_method }}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <span class="badge badge-{{ $payout->status === 'paid' ? 'success' : ($payout->status === 'processing' ? 'info' : ($payout->status === 'failed' ? 'danger' : 'warning')) }}">
                        {{ ucfirst($payout->status) }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Created:</strong> {{ $payout->created_at->format('M d, Y H:i') }}
                </div>
                @if($payout->paid_at)
                <div class="mb-3">
                    <strong>Paid At:</strong> {{ $payout->paid_at->format('M d, Y H:i') }}
                </div>
                @endif
                @if($payout->notes)
                <div class="mb-3">
                    <strong>Notes:</strong>
                    <p>{{ $payout->notes }}</p>
                </div>
                @endif

                @if($payout->status !== 'paid')
                <form action="{{ route('admin.payouts.mark-as-paid', $payout) }}" method="POST" class="mt-4" onsubmit="return confirm('Mark this payout as paid?');">
                    @csrf
                    @method('POST')
                    <button type="submit" class="adomx-btn adomx-btn-success">
                        <i class="fas fa-check"></i> Mark as Paid
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Commissions Included -->
        @if($payout->commissions->isNotEmpty())
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Commissions Included</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Order</th>
                                <th>Amount</th>
                                <th>Rate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payout->commissions as $commission)
                            <tr>
                                <td>{{ $commission->course->title ?? 'N/A' }}</td>
                                <td>#{{ substr($commission->order->id, 0, 8) }}</td>
                                <td>${{ number_format($commission->amount, 2) }}</td>
                                <td>{{ number_format($commission->commission_rate * 100, 1) }}%</td>
                                <td>
                                    <span class="badge badge-{{ $commission->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($commission->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Teacher Information</h3>
            </div>
            <div class="adomx-card-body">
                <div class="mb-3">
                    <strong>Name:</strong> {{ $payout->teacher->name }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $payout->teacher->email }}
                </div>
                <div class="mb-3">
                    <strong>Total Commissions:</strong> ${{ number_format($payout->teacher->commissions()->sum('amount'), 2) }}
                </div>
                <div class="mb-3">
                    <strong>Total Payouts:</strong> ${{ number_format($payout->teacher->payouts()->where('status', 'paid')->sum('amount'), 2) }}
                </div>
                <div>
                    <strong>Pending Balance:</strong> 
                    <span style="color: var(--primary-color); font-weight: bold;">
                        ${{ number_format($payout->teacher->commissions()->where('status', 'pending')->sum('amount'), 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

