@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Leaderboard</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.gamification.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Top Students by XP</h3>
    </div>
    <div class="adomx-card-body">
        <div class="table-responsive">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Rank</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>XP Points</th>
                        <th>Badges</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaderboard as $index => $user)
                    <tr>
                        <td>
                            @if($index < 3)
                            <span style="font-size: 24px;">
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @else 🥉
                                @endif
                            </span>
                            @else
                            <strong>#{{ ($leaderboard->currentPage() - 1) * $leaderboard->perPage() + $index + 1 }}</strong>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-success">Level {{ $user->level ?? 1 }}</span>
                        </td>
                        <td>
                            <strong>{{ number_format($user->xp_points ?? 0) }}</strong> XP
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $user->badges_count ?? 0 }} Badges</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No students found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $leaderboard->links() }}
    </div>
</div>
@endsection

