@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Gamification Progress</h1>
        <div>
            <a href="{{ route('gamification.leaderboard') }}" class="btn btn-secondary">
                <i class="fas fa-trophy"></i> Leaderboard
            </a>
            <a href="{{ route('gamification.badges') }}" class="btn btn-secondary">
                <i class="fas fa-medal"></i> All Badges
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ number_format($stats['xp_points']) }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total XP</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">Level {{ $stats['level'] }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Current Level</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $stats['badges_count'] }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Badges Earned</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ number_format($stats['next_level_xp']) }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">XP for Next Level</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Level Progress -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Level Progress</h5>
        </div>
        <div class="card-body">
            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <span>Level {{ $stats['level'] }}</span>
                    <span>Level {{ $stats['level'] + 1 }}</span>
                </div>
            </div>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $xpProgress }}%;" aria-valuenow="{{ $xpProgress }}" aria-valuemin="0" aria-valuemax="100">
                    {{ number_format($xpProgress, 1) }}%
                </div>
            </div>
            <div class="mt-2 text-center">
                <small class="text-muted">
                    {{ number_format($stats['xp_points'] - $stats['current_level_xp']) }} / {{ number_format($stats['next_level_xp'] - $stats['current_level_xp']) }} XP
                </small>
            </div>
        </div>
    </div>

    <!-- XP Breakdown -->
    @if($xpBySource->isNotEmpty())
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>XP by Source</h5>
                </div>
                <div class="card-body">
                    <canvas id="xpSourceChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>My Badges</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($badges as $badge)
                        <div class="col-md-6 mb-3 text-center">
                            <div style="font-size: 48px;">
                                @if($badge->icon)
                                <i class="{{ $badge->icon }}" style="color: {{ $badge->color ?? '#007bff' }};"></i>
                                @else
                                <i class="fas fa-trophy" style="color: {{ $badge->color ?? '#007bff' }};"></i>
                                @endif
                            </div>
                            <strong>{{ $badge->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $badge->pivot->earned_at->format('M d, Y') }}</small>
                        </div>
                        @empty
                        <div class="col-md-12 text-center text-muted">
                            <p>You haven't earned any badges yet. Keep learning to unlock badges!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent XP Transactions -->
    <div class="card">
        <div class="card-header">
            <h5>Recent XP Transactions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Points</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $transaction->type === 'earned' ? 'success' : ($transaction->type === 'bonus' ? 'info' : 'warning') }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-{{ $transaction->type === 'earned' || $transaction->type === 'bonus' ? 'success' : 'danger' }}">
                                    {{ $transaction->type === 'earned' || $transaction->type === 'bonus' ? '+' : '-' }}{{ $transaction->points }}
                                </strong>
                            </td>
                            <td>{{ $transaction->description ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No XP transactions yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const xpSourceData = @json($xpBySource);
    const xpSourceCtx = document.getElementById('xpSourceChart');
    if (xpSourceCtx) {
        new Chart(xpSourceCtx, {
            type: 'doughnut',
            data: {
                labels: xpSourceData.map(item => item.source_type ? item.source_type.split('\\').pop() : 'Other'),
                datasets: [{
                    data: xpSourceData.map(item => item.total),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ]
                }]
            }
        });
    }
</script>
@endpush
@endsection

