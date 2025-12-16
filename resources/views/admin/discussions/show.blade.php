@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Discussion Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.discussions.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="margin-bottom: 30px;">
            <h3>Discussion Information</h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>User:</strong> {{ $discussion->user->name ?? 'N/A' }}
            </div>
            <div>
                <strong>Course:</strong> {{ $discussion->course->title ?? 'N/A' }}
            </div>
            <div>
                <strong>Status:</strong>
                <span class="adomx-status-badge adomx-status-{{ $discussion->status ?? 'pending' }}">
                    {{ ucfirst($discussion->status ?? 'pending') }}
                </span>
            </div>
            <div>
                <strong>Created:</strong> {{ $discussion->created_at->format('M d, Y H:i') }}
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <strong>Message:</strong>
            <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px;">
                {{ $discussion->message }}
            </div>
        </div>

        @if($discussion->replies->count() > 0)
            <h4 style="margin-bottom: 20px;">Replies ({{ $discussion->replies->count() }})</h4>
            @foreach($discussion->replies as $reply)
                <div style="margin-bottom: 15px; padding: 15px; background: var(--bg-secondary); border-radius: 8px; border-left: 3px solid var(--primary-color);">
                    <strong>{{ $reply->user->name ?? 'N/A' }}</strong>
                    <span style="color: var(--text-secondary); font-size: 12px;">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                    <p style="margin-top: 10px;">{{ $reply->message }}</p>
                </div>
            @endforeach
        @endif

        <div class="adomx-form-actions">
            @if(($discussion->status ?? 'pending') != 'approved')
                <form action="{{ route('admin.discussions.approve', $discussion) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-check"></i>
                        Approve Discussion
                    </button>
                </form>
            @endif
            @if(($discussion->status ?? 'pending') != 'rejected')
                <form action="{{ route('admin.discussions.reject', $discussion) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="adomx-btn" style="background: var(--warning-color);">
                        <i class="fas fa-times"></i>
                        Reject Discussion
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.discussions.destroy', $discussion) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="adomx-btn" style="background: var(--danger-color);">
                    <i class="fas fa-trash"></i>
                    Delete Discussion
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

