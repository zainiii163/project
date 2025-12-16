@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Subscription Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.subscriptions.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Subscription
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Subscriptions</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.subscriptions.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search by user..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <select name="plan" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Plans</option>
                <option value="basic" {{ request('plan') == 'basic' ? 'selected' : '' }}>Basic</option>
                <option value="premium" {{ request('plan') == 'premium' ? 'selected' : '' }}>Premium</option>
                <option value="enterprise" {{ request('plan') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                    <tr>
                        <td><strong>{{ $subscription->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ ucfirst($subscription->plan) }}</td>
                        <td>${{ number_format($subscription->amount, 2) }}</td>
                        <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                        <td>{{ $subscription->end_date->format('M d, Y') }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $subscription->status }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="adomx-action-btn" title="Delete" style="color: var(--danger-color);">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="adomx-table-empty">No subscriptions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection

