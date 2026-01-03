@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Leaderboard</h1>
        <div>
            <a href="{{ route('gamification.my-progress') }}" class="btn btn-primary">
                <i class="fas fa-user"></i> My Progress
            </a>
            <a href="{{ route('gamification.badges') }}" class="btn btn-secondary">
                <i class="fas fa-trophy"></i> Badges
            </a>
        </div>
    </div>

    @if($userRank)
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle"></i> Your current rank: <strong>#{{ $userRank }}</strong>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Top 100 Students</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Rank</th>
                            <th>Student</th>
                            <th>Level</th>
                            <th>XP Points</th>
                            <th>Badges</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaderboard as $index => $user)
                        <tr class="{{ auth()->id() === $user->id ? 'table-primary' : '' }}">
                            <td>
                                @if($index < 3)
                                <span style="font-size: 24px;">
                                    @if($index === 0) 🥇
                                    @elseif($index === 1) 🥈
                                    @else 🥉
                                    @endif
                                </span>
                                @else
                                <strong>#{{ $index + 1 }}</strong>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if(auth()->id() === $user->id)
                                        <span class="badge badge-primary">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

