@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Gamification Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.gamification.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i> Create Badge
        </a>
        <a href="{{ route('admin.gamification.leaderboard') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-trophy"></i> View Leaderboard
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="adomx-row mb-4">
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $totalUsers }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Students</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ number_format($totalXp) }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total XP</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ number_format($avgLevel, 1) }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Average Level</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $badges->total() }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Badges</p>
            </div>
        </div>
    </div>
</div>

<!-- Badges List -->
<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Badges</h3>
    </div>
    <div class="adomx-card-body">
        <div class="table-responsive">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Badge</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Required XP</th>
                        <th>Earned By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($badges as $badge)
                    <tr>
                        <td>
                            <div style="font-size: 32px;">
                                @if($badge->icon)
                                <i class="{{ $badge->icon }}" style="color: {{ $badge->color ?? '#007bff' }};"></i>
                                @else
                                <i class="fas fa-trophy" style="color: {{ $badge->color ?? '#007bff' }};"></i>
                                @endif
                            </div>
                        </td>
                        <td><strong>{{ $badge->name }}</strong></td>
                        <td>
                            <span class="badge badge-{{ $badge->type === 'achievement' ? 'primary' : ($badge->type === 'completion' ? 'success' : ($badge->type === 'participation' ? 'info' : 'warning')) }}">
                                {{ ucfirst($badge->type) }}
                            </span>
                        </td>
                        <td>{{ $badge->required_xp ? number_format($badge->required_xp) : 'N/A' }}</td>
                        <td>{{ $badge->users_count ?? 0 }} students</td>
                        <td>
                            <span class="badge badge-{{ $badge->is_active ? 'success' : 'secondary' }}">
                                {{ $badge->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.gamification.edit', $badge) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.gamification.destroy', $badge) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this badge?');">
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
                        <td colspan="7" class="text-center">No badges found. <a href="{{ route('admin.gamification.create') }}">Create your first badge</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $badges->links() }}
    </div>
</div>
@endsection

