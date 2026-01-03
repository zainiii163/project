@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        @if(auth()->user()->isTeacher())
        <a href="{{ route('teacher.live-sessions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Sessions
        </a>
        @else
        <a href="{{ route('student.live-sessions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Sessions
        </a>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h2>{{ $liveSession->title }}</h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Course:</strong> {{ $liveSession->course->title ?? 'N/A' }}</p>
                    <p><strong>Teacher:</strong> {{ $liveSession->teacher->name ?? 'N/A' }}</p>
                    <p><strong>Platform:</strong> 
                        <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $liveSession->platform)) }}</span>
                    </p>
                    <p><strong>Scheduled At:</strong> {{ $liveSession->scheduled_at->format('M d, Y H:i') }}</p>
                    <p><strong>Duration:</strong> {{ $liveSession->duration_minutes }} minutes</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $liveSession->status === 'live' ? 'success' : ($liveSession->status === 'scheduled' ? 'info' : ($liveSession->status === 'completed' ? 'secondary' : 'warning')) }}">
                            {{ ucfirst($liveSession->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    @if($liveSession->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $liveSession->description }}</p>
                    </div>
                    @endif
                    @if($liveSession->meeting_password)
                    <div class="mb-3">
                        <strong>Meeting Password:</strong>
                        <code>{{ $liveSession->meeting_password }}</code>
                    </div>
                    @endif
                </div>
            </div>

            @if($liveSession->status === 'scheduled' || $liveSession->status === 'live')
            <div class="text-center">
                @if($liveSession->scheduled_at <= now() || $liveSession->status === 'live')
                @if(auth()->user()->isTeacher())
                <form action="{{ route('teacher.live-sessions.join', $liveSession) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-video"></i> Join Live Session
                    </button>
                </form>
                @else
                <form action="{{ route('student.live-sessions.join', $liveSession) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-video"></i> Join Live Session
                    </button>
                </form>
                @endif
                @else
                <div class="alert alert-info">
                    <i class="fas fa-clock"></i> This session will start on {{ $liveSession->scheduled_at->format('M d, Y at H:i') }}
                </div>
                @endif
            </div>
            @elseif($liveSession->status === 'completed')
            <div class="alert alert-secondary text-center">
                <i class="fas fa-check-circle"></i> This session has been completed.
            </div>
            @else
            <div class="alert alert-warning text-center">
                <i class="fas fa-times-circle"></i> This session has been cancelled.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

