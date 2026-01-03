@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Badges</h1>
        <div>
            <a href="{{ route('gamification.leaderboard') }}" class="btn btn-secondary">
                <i class="fas fa-trophy"></i> Leaderboard
            </a>
            <a href="{{ route('gamification.my-progress') }}" class="btn btn-primary">
                <i class="fas fa-user"></i> My Progress
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h3>Your Badges: {{ $userBadges->count() }}/{{ $badges->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($badges as $badge)
        <div class="col-md-4 mb-4">
            <div class="card h-100 {{ $userBadges->contains('id', $badge->id) ? 'border-success' : 'border-secondary' }}" style="opacity: {{ $userBadges->contains('id', $badge->id) ? '1' : '0.6' }};">
                <div class="card-body text-center">
                    <div style="font-size: 64px; margin-bottom: 15px;">
                        @if($badge->icon)
                        <i class="{{ $badge->icon }}" style="color: {{ $badge->color ?? '#007bff' }};"></i>
                        @else
                        <i class="fas fa-trophy" style="color: {{ $badge->color ?? '#007bff' }};"></i>
                        @endif
                    </div>
                    <h4>{{ $badge->name }}</h4>
                    <p class="text-muted">{{ $badge->description }}</p>
                    <div class="mb-2">
                        <span class="badge badge-{{ $badge->type === 'achievement' ? 'primary' : ($badge->type === 'completion' ? 'success' : ($badge->type === 'participation' ? 'info' : 'warning')) }}">
                            {{ ucfirst($badge->type) }}
                        </span>
                    </div>
                    @if($badge->required_xp)
                    <small class="text-muted">Requires: {{ number_format($badge->required_xp) }} XP</small>
                    @endif
                    @if($userBadges->contains('id', $badge->id))
                    <div class="mt-2">
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> Earned
                        </span>
                    </div>
                    @else
                    <div class="mt-2">
                        <span class="badge badge-secondary">
                            <i class="fas fa-lock"></i> Locked
                        </span>
                    </div>
                    @endif
                    <div class="mt-2">
                        <small class="text-muted">{{ $badge->users_count ?? 0 }} students earned this badge</small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

