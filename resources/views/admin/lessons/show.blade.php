@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>{{ $lesson->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.lessons.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Lessons
        </a>
        <a href="{{ route('admin.lessons.edit', $lesson) }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-edit"></i>
            Edit Lesson
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <!-- Main Content -->
    <div>
        <div class="adomx-card" style="margin-bottom: 20px;">
            <div class="adomx-card-header">
                <h3>Lesson Details</h3>
            </div>
            <div class="adomx-card-body">
                <div style="margin-bottom: 20px;">
                    <strong>Title:</strong>
                    <p style="margin-top: 5px; color: var(--text-secondary);">{{ $lesson->title }}</p>
                </div>

                @if($lesson->description)
                <div style="margin-bottom: 20px;">
                    <strong>Description:</strong>
                    <div style="margin-top: 5px; color: var(--text-secondary); line-height: 1.8;">
                        {!! nl2br(e($lesson->description)) !!}
                    </div>
                </div>
                @endif

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div>
                        <strong>Course:</strong>
                        <p style="margin-top: 5px; color: var(--text-secondary);">
                            <a href="{{ route('admin.courses.show', $lesson->course) }}">{{ $lesson->course->title }}</a>
                        </p>
                    </div>
                    <div>
                        <strong>Type:</strong>
                        <p style="margin-top: 5px; color: var(--text-secondary);">{{ ucfirst($lesson->type ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <strong>Duration:</strong>
                        <p style="margin-top: 5px; color: var(--text-secondary);">
                            @if($lesson->duration)
                                {{ gmdate('H:i', $lesson->duration * 60) }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <strong>Order:</strong>
                        <p style="margin-top: 5px; color: var(--text-secondary);">{{ $lesson->order ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong>Preview:</strong>
                        <p style="margin-top: 5px; color: var(--text-secondary);">
                            @if($lesson->is_preview)
                                <span class="adomx-status-badge adomx-status-published">Yes</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">No</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($lesson->content_url)
                <div style="margin-bottom: 20px;">
                    <strong>Content URL:</strong>
                    <p style="margin-top: 5px; color: var(--text-secondary); word-break: break-all;">
                        <a href="{{ asset('storage/' . $lesson->content_url) }}" target="_blank">
                            {{ $lesson->content_url }}
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quiz Section -->
        @if($lesson->quiz)
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Associated Quiz</h3>
            </div>
            <div class="adomx-card-body">
                <p><strong>Quiz:</strong> <a href="{{ route('admin.quizzes.show', $lesson->quiz) }}">{{ $lesson->quiz->title }}</a></p>
                <p><strong>Questions:</strong> {{ $lesson->quiz->questions->count() }}</p>
                <p><strong>Pass Score:</strong> {{ $lesson->quiz->pass_score }}%</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div>
        <div class="adomx-card" style="margin-bottom: 20px;">
            <div class="adomx-card-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="adomx-card-body">
                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="adomx-btn adomx-btn-primary" style="width: 100%; margin-bottom: 10px;">
                    <i class="fas fa-edit"></i> Edit Lesson
                </a>
                <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="adomx-btn adomx-btn-danger" style="width: 100%;">
                        <i class="fas fa-trash"></i> Delete Lesson
                    </button>
                </form>
            </div>
        </div>

        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Course Information</h3>
            </div>
            <div class="adomx-card-body">
                <p><strong>Course:</strong> <a href="{{ route('admin.courses.show', $lesson->course) }}">{{ $lesson->course->title }}</a></p>
                <p><strong>Teacher:</strong> {{ $lesson->course->teacher->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    <span class="adomx-status-badge adomx-status-{{ $lesson->course->status }}">
                        {{ ucfirst($lesson->course->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

