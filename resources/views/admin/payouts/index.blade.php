@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Payouts & Commissions</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payouts.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i> Create Payout
        </a>
    </div>
</div>

<!-- Filters -->
<div class="adomx-card mb-4">
    <div class="adomx-card-body">
        <form method="GET" action="{{ route('admin.payouts.index') }}" class="row g-3">
            <div class="col-md-4">
                <label>Teacher</label>
                <select name="teacher_id" class="form-control">
                    <option value="">All Teachers</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Payouts List -->
<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Payouts</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Teacher</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Paid At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $payout)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">
                                    {{ strtoupper(substr($payout->teacher->name, 0, 1)) }}
                                </div>
                                <span>{{ $payout->teacher->name }}</span>
                            </div>
                        </td>
                        <td><strong>${{ number_format($payout->amount, 2) }}</strong></td>
                        <td>{{ $payout->payment_method }}</td>
                        <td>
                            <span class="badge badge-{{ $payout->status === 'paid' ? 'success' : ($payout->status === 'processing' ? 'info' : ($payout->status === 'failed' ? 'danger' : 'warning')) }}">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                        <td>{{ $payout->created_at->format('M d, Y') }}</td>
                        <td>{{ $payout->paid_at ? $payout->paid_at->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.payouts.show', $payout) }}" class="adomx-action-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($payout->status === 'pending' || $payout->status === 'processing')
                            <form action="{{ route('admin.payouts.mark-as-paid', $payout) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this payout as paid?');">
                                @csrf
                                @method('POST')
                                <button type="submit" class="adomx-action-btn text-success" title="Mark as Paid">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No payouts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adomx-table-footer">
            {{ $payouts->links() }}
        </div>
    </div>
</div>
@endsection

