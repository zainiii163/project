@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Live Sessions</h1>
        @if(auth()->user()->isTeacher())
        <a href="{{ route('teacher.live-sessions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Session
        </a>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h5>My Live Sessions</h5>
        </div>
        <div class="card-body">
            @forelse($sessions as $session)
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4>{{ $session->title }}</h4>
                        <p class="text-muted mb-2">{{ $session->description }}</p>
                        <div class="mb-2">
                            <strong>Course:</strong> {{ $session->course->title ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Teacher:</strong> {{ $session->teacher->name ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Scheduled:</strong> {{ $session->scheduled_at->format('M d, Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <strong>Duration:</strong> {{ $session->duration_minutes }} minutes
                        </div>
                        <div class="mb-2">
                            <strong>Platform:</strong> 
                            <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $session->platform)) }}</span>
                        </div>
                        <div>
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $session->status === 'live' ? 'success' : ($session->status === 'scheduled' ? 'info' : ($session->status === 'completed' ? 'secondary' : 'warning')) }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        @if($session->status === 'scheduled' || $session->status === 'live')
                        @if(auth()->user()->isTeacher())
                        <a href="{{ route('teacher.live-sessions.show', $session) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @else
                        <a href="{{ route('student.live-sessions.show', $session) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-video fa-3x text-muted mb-3"></i>
                <p class="text-muted">No live sessions available.</p>
            </div>
            @endforelse
        </div>
        <div class="card-footer">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
@endsection

