@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Subscriptions</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Active Subscriptions</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Subscription Plan</th>
                    <th>Started</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th>Courses Included</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                    @php
                        $pivot = $subscription->pivot ?? null;
                        $isActive = $pivot && $pivot->expires_at && $pivot->expires_at->isFuture();
                    @endphp
                    <tr>
                        <td><strong>{{ $subscription->name ?? 'N/A' }}</strong></td>
                        <td>{{ $pivot->started_at->format('M d, Y') ?? 'N/A' }}</td>
                        <td>
                            @if($pivot->expires_at)
                                {{ $pivot->expires_at->format('M d, Y') }}
                                @if($pivot->expires_at->isPast())
                                    <br><small style="color: var(--danger-color);">Expired</small>
                                @else
                                    <br><small style="color: var(--text-secondary);">
                                        {{ $pivot->expires_at->diffForHumans() }}
                                    </small>
                                @endif
                            @else
                                Never
                            @endif
                        </td>
                        <td>
                            @if($isActive)
                                <span class="adomx-status-badge adomx-status-published">Active</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">Expired</span>
                            @endif
                        </td>
                        <td>
                            {{ $subscription->courses->count() ?? 0 }} courses
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="#" class="adomx-action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$isActive)
                                    <a href="{{ route('student.payments.purchase-subscription', $subscription) }}" class="adomx-action-btn" title="Renew" style="color: var(--primary-color);">
                                        <i class="fas fa-sync"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">
                            No active subscriptions. Browse subscription plans to get started!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(count($subscriptions) > 0)
<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-header">
        <h3>Subscription Benefits</h3>
    </div>
    <div class="adomx-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            @foreach($subscriptions as $subscription)
                @if($subscription->pivot && $subscription->pivot->expires_at && $subscription->pivot->expires_at->isFuture())
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <h4 style="margin-bottom: 15px;">{{ $subscription->name }}</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 8px;">
                                <i class="fas fa-check" style="color: var(--success-color); margin-right: 8px;"></i>
                                Access to {{ $subscription->courses->count() }} courses
                            </li>
                            <li style="margin-bottom: 8px;">
                                <i class="fas fa-check" style="color: var(--success-color); margin-right: 8px;"></i>
                                Valid until {{ $subscription->pivot->expires_at->format('M d, Y') }}
                            </li>
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

