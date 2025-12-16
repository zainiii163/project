@extends('layouts.admin')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Course Discussions</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">{{ $course->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <button type="button" class="adomx-btn adomx-btn-primary" onclick="document.getElementById('create-discussion-form').style.display='block'">
            <i class="fas fa-plus"></i>
            New Discussion
        </button>
        <a href="{{ route('student.courses.show', $course) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Course
        </a>
    </div>
</div>

<div id="create-discussion-form" style="display: none; margin-bottom: 20px;">
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Create New Discussion</h3>
        </div>
        <div class="adomx-card-body">
            <form action="{{ route('student.community.create-discussion', $course) }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label class="adomx-label">Title</label>
                    <input type="text" name="title" class="adomx-input" placeholder="Discussion title..." required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label class="adomx-label">Content</label>
                    <textarea name="content" class="adomx-input" rows="5" placeholder="What would you like to discuss?" required></textarea>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Post Discussion
                    </button>
                    <button type="button" class="adomx-btn adomx-btn-secondary" onclick="document.getElementById('create-discussion-form').style.display='none'">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>All Discussions</h3>
    </div>
    <div class="adomx-card-body">
        @forelse($discussions as $discussion)
            <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 10px;">
                            <a href="#" style="color: var(--primary-color); text-decoration: none;">
                                {{ $discussion->title }}
                            </a>
                        </h4>
                        <div style="display: flex; align-items: center; gap: 15px; color: var(--text-secondary); font-size: 14px;">
                            <span>
                                <i class="fas fa-user"></i>
                                {{ $discussion->user->name }}
                            </span>
                            <span>
                                <i class="fas fa-clock"></i>
                                {{ $discussion->created_at->diffForHumans() }}
                            </span>
                            <span>
                                <i class="fas fa-comments"></i>
                                {{ $discussion->replies->count() ?? 0 }} replies
                            </span>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 15px; color: var(--text-color);">
                    {{ Str::limit($discussion->content, 200) }}
                </div>

                <div>
                    <button type="button" class="adomx-btn adomx-btn-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="toggleReply({{ $discussion->id }})">
                        <i class="fas fa-reply"></i>
                        Reply
                    </button>
                </div>

                <div id="reply-form-{{ $discussion->id }}" style="display: none; margin-top: 15px; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                    <form action="{{ route('student.community.reply-discussion', $discussion) }}" method="POST">
                        @csrf
                        <textarea name="content" class="adomx-input" rows="3" placeholder="Write your reply..." required></textarea>
                        <div style="margin-top: 10px; display: flex; gap: 10px;">
                            <button type="submit" class="adomx-btn adomx-btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                Post Reply
                            </button>
                            <button type="button" class="adomx-btn adomx-btn-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="toggleReply({{ $discussion->id }})">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

                @if($discussion->replies && $discussion->replies->count() > 0)
                    <div style="margin-top: 20px; padding-left: 20px; border-left: 3px solid var(--border-color);">
                        <h5 style="margin-bottom: 15px; color: var(--text-secondary);">Replies ({{ $discussion->replies->count() }})</h5>
                        @foreach($discussion->replies as $reply)
                            <div style="margin-bottom: 15px; padding: 10px; background: var(--card-bg); border-radius: 4px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <strong>{{ $reply->user->name ?? 'Anonymous' }}</strong>
                                    <small style="color: var(--text-secondary);">{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                                <div>{{ $reply->content }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="adomx-table-empty">
                No discussions yet. Be the first to start a discussion!
            </div>
        @endforelse
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $discussions->links() }}
    </div>
</div>

<script>
    function toggleReply(discussionId) {
        const form = document.getElementById('reply-form-' + discussionId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection

