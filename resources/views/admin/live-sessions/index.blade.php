@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Live Sessions</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.live-sessions.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i> Create Live Session
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">All Live Sessions</h3>
    </div>
    <div class="adomx-card-body">
        <div class="table-responsive">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Platform</th>
                        <th>Scheduled At</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr>
                        <td><strong>{{ $session->title }}</strong></td>
                        <td>{{ $session->course->title ?? 'N/A' }}</td>
                        <td>{{ $session->teacher->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ ucfirst(str_replace('_', ' ', $session->platform)) }}
                            </span>
                        </td>
                        <td>{{ $session->scheduled_at->format('M d, Y H:i') }}</td>
                        <td>{{ $session->duration_minutes }} mins</td>
                        <td>
                            <span class="badge badge-{{ $session->status === 'live' ? 'success' : ($session->status === 'scheduled' ? 'info' : ($session->status === 'completed' ? 'secondary' : 'warning')) }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.live-sessions.edit', $session) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($session->status === 'scheduled')
                                <form action="{{ route('admin.live-sessions.start', $session) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="adomx-action-btn" title="Start Session" style="color: var(--success-color);">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                                @endif
                                @if($session->status === 'live')
                                <form action="{{ route('admin.live-sessions.end', $session) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="adomx-action-btn" title="End Session" style="color: var(--warning-color);">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.live-sessions.destroy', $session) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this session?');">
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
                        <td colspan="8" class="text-center">No live sessions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $sessions->links() }}
    </div>
</div>
@endsection

