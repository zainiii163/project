@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>{{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px;">
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Course Progress</h3>
        </div>
        <div class="adomx-card-body">
            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Overall Progress</span>
                    <strong>{{ $progress }}%</strong>
                </div>
                <div class="adomx-progress-bar">
                    <div class="adomx-progress-fill" style="width: {{ $progress }}%; background: var(--primary-color);">
                        <span style="font-size: 11px; padding: 0 5px; color: white;">{{ $progress }}%</span>
                    </div>
                </div>
            </div>
            
            <h4 style="margin-bottom: 15px;">Lessons</h4>
            @foreach($course->lessons->sortBy('order') as $lesson)
                <div style="padding: 10px; margin-bottom: 10px; background: var(--bg-secondary); border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>{{ $lesson->title }}</strong>
                        <small style="display: block; color: var(--text-secondary);">{{ ucfirst($lesson->content_type) }}</small>
                    </div>
                    <div>
                        @if(in_array($lesson->id, $completedLessons->toArray()))
                            <span class="adomx-status-badge adomx-status-published">
                                <i class="fas fa-check"></i> Completed
                            </span>
                        @else
                            <a href="{{ route('lessons.show', ['course' => $course->slug, 'lesson' => $lesson->id]) }}" class="adomx-btn adomx-btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-play"></i> Start
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div>
        <div class="adomx-card" style="margin-bottom: 20px;">
            <div class="adomx-card-header">
                <h3>Course Info</h3>
            </div>
            <div class="adomx-card-body">
                <p><strong>Teacher:</strong> {{ $course->teacher->name ?? 'N/A' }}</p>
                <p><strong>Category:</strong> {{ $course->category->name ?? 'Uncategorized' }}</p>
                <p><strong>Level:</strong> {{ ucfirst($course->level ?? 'N/A') }}</p>
            </div>
        </div>

        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Quick Links</h3>
            </div>
            <div class="adomx-card-body" style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('discussions.index', $course) }}" class="adomx-btn" style="background: var(--info-color);">
                    <i class="fas fa-comments"></i>
                    Discussions
                </a>
                <a href="{{ route('courses.show', $course->slug) }}" class="adomx-btn" style="background: var(--warning-color);">
                    <i class="fas fa-info-circle"></i>
                    Course Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

