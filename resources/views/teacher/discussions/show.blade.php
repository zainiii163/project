@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Discussion Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.discussions.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>Student:</strong> {{ $discussion->user->name ?? 'N/A' }}
            </div>
            <div>
                <strong>Course:</strong> {{ $discussion->course->title ?? 'N/A' }}
            </div>
            <div>
                <strong>Created:</strong> {{ $discussion->created_at->format('M d, Y H:i') }}
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <strong>Question:</strong>
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

        <form action="{{ route('teacher.discussions.reply', $discussion) }}" method="POST" style="margin-top: 30px;">
            @csrf
            <div class="adomx-form-group">
                <label for="message" class="adomx-label">Your Reply <span class="text-danger">*</span></label>
                <textarea id="message" name="message" class="adomx-input" rows="4" required></textarea>
            </div>
            <button type="submit" class="adomx-btn adomx-btn-primary">
                <i class="fas fa-reply"></i>
                Post Reply
            </button>
        </form>
    </div>
</div>
@endsection

