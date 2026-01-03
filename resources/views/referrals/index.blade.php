@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Referral Program</h2>
    </div>
    <div class="adomx-page-actions">
        @if(!auth()->user()->referral_code)
            <form action="{{ route('referrals.generate-code') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-plus"></i> Generate Referral Code
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Statistics Cards -->
<div class="adomx-row" style="margin-bottom: 20px;">
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Referrals</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">{{ $stats['total_referrals'] }}</div>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(79, 70, 229, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users" style="font-size: 20px; color: var(--primary-color);"></i>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Completed</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">{{ $stats['completed_referrals'] }}</div>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Pending</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">{{ $stats['pending_referrals'] }}</div>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Rewards</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">${{ number_format($stats['total_rewards'], 2) }}</div>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-dollar-sign" style="font-size: 20px; color: var(--info-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Referral Code Section -->
@if(auth()->user()->referral_code)
<div class="adomx-card" style="margin-bottom: 20px;">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Your Referral Code</h3>
    </div>
    <div class="adomx-card-body">
        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Referral Code</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="referralCode" value="{{ auth()->user()->referral_code }}" readonly 
                           style="flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 18px; font-weight: bold; letter-spacing: 2px; text-align: center;">
                    <button onclick="copyReferralCode()" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Referral Link</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="referralLink" value="{{ route('register') }}?ref={{ auth()->user()->referral_code }}" readonly 
                           style="flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px;">
                    <button onclick="copyReferralLink()" class="adomx-btn adomx-btn-secondary">
                        <i class="fas fa-link"></i> Copy Link
                    </button>
                </div>
            </div>
        </div>
        <div style="margin-top: 15px; padding: 15px; background: var(--bg-light); border-radius: 4px;">
            <p style="margin: 0; color: var(--text-secondary);">
                <i class="fas fa-info-circle"></i> Share your referral code or link with others. When they sign up and make their first purchase, you'll earn rewards!
            </p>
        </div>
    </div>
</div>
@endif

<!-- Referrals List -->
<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">My Referrals</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Referred User</th>
                        <th>Referral Code</th>
                        <th>Status</th>
                        <th>Reward Amount</th>
                        <th>Completed At</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $referral)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                        {{ substr($referral->referred->name ?? 'N/A', 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 500;">{{ $referral->referred->name ?? 'N/A' }}</div>
                                        <div style="font-size: 12px; color: var(--text-secondary);">{{ $referral->referred->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code style="padding: 5px 10px; background: var(--bg-light); border-radius: 4px; font-size: 12px;">
                                    {{ $referral->referral_code }}
                                </code>
                            </td>
                            <td>
                                <span class="adomx-status-badge adomx-status-{{ $referral->status }}">
                                    {{ ucfirst($referral->status) }}
                                </span>
                            </td>
                            <td>
                                @if($referral->reward_amount)
                                    <strong style="color: var(--success-color);">${{ number_format($referral->reward_amount, 2) }}</strong>
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td>
                                @if($referral->completed_at)
                                    {{ $referral->completed_at->format('M d, Y H:i') }}
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td>{{ $referral->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="adomx-table-empty">
                                <div style="text-align: center; padding: 40px;">
                                    <i class="fas fa-user-friends" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                                    <p style="color: var(--text-secondary); margin-bottom: 20px;">No referrals yet. Start sharing your referral code!</p>
                                    @if(auth()->user()->referral_code)
                                        <button onclick="copyReferralLink()" class="adomx-btn adomx-btn-primary">
                                            <i class="fas fa-share"></i> Share Referral Link
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
            {{ $referrals->links() }}
        </div>
    </div>
</div>

<script>
function copyReferralCode() {
    const code = document.getElementById('referralCode');
    code.select();
    document.execCommand('copy');
    
    // Show toast notification
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
    btn.style.background = 'var(--success-color)';
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.style.background = '';
    }, 2000);
}

function copyReferralLink() {
    const link = document.getElementById('referralLink');
    link.select();
    document.execCommand('copy');
    
    // Show toast notification
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
    btn.style.background = 'var(--success-color)';
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.style.background = '';
    }, 2000);
}
</script>
@endsection

