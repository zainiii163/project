@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>{{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Courses
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px;">
    <!-- Course Thumbnail/Image -->
    <div class="adomx-card">
        @if($course->thumbnail)
            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width: 100%; height: 400px; object-fit: cover; border-radius: 8px 8px 0 0;">
        @else
            <div style="width: 100%; height: 400px; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; border-radius: 8px 8px 0 0;">
                <i class="fas fa-book" style="font-size: 72px; color: var(--text-secondary);"></i>
            </div>
        @endif
        <div class="adomx-card-body">
            <h3 style="margin-bottom: 15px;">About This Course</h3>
            <div style="color: var(--text-secondary); line-height: 1.8;">
                {!! nl2br(e($course->description ?? 'No description available.')) !!}
            </div>

            @if($course->objectives)
                <div style="margin-top: 30px;">
                    <h4 style="margin-bottom: 10px;">What You'll Learn</h4>
                    <div style="color: var(--text-secondary);">
                        {!! nl2br(e($course->objectives)) !!}
                    </div>
                </div>
            @endif

            @if($course->requirements)
                <div style="margin-top: 30px;">
                    <h4 style="margin-bottom: 10px;">Requirements</h4>
                    <div style="color: var(--text-secondary);">
                        {!! nl2br(e($course->requirements)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Course Info Sidebar -->
    <div>
        <div class="adomx-card" style="margin-bottom: 20px;">
            <div class="adomx-card-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 36px; font-weight: bold; color: var(--primary-color); margin-bottom: 5px;">
                        ${{ number_format($course->price, 2) }}
                    </div>
                    @if($course->price > 0)
                        <div style="color: var(--text-secondary); font-size: 14px;">One-time payment</div>
                    @else
                        <div style="color: var(--success-color); font-size: 14px;">Free Course</div>
                    @endif
                </div>

                @if($isEnrolled)
                    <a href="{{ route('student.courses.show', $course) }}" class="adomx-btn adomx-btn-primary" style="width: 100%; margin-bottom: 10px;">
                        <i class="fas fa-play"></i>
                        Continue Learning
                    </a>
                    @if($firstLesson)
                        <a href="{{ route('lessons.show', ['course' => $course->slug, 'lesson' => $firstLesson->id]) }}" class="adomx-btn" style="width: 100%; background: var(--info-color);">
                            <i class="fas fa-book-open"></i>
                            Start from Beginning
                        </a>
                    @endif
                @else
                    <form action="{{ route('courses.enroll', $course) }}" method="POST" style="margin-bottom: 10px;">
                        @csrf
                        <button type="submit" class="adomx-btn adomx-btn-primary" style="width: 100%;">
                            <i class="fas fa-shopping-cart"></i>
                            {{ $course->price > 0 ? 'Enroll Now' : 'Enroll for Free' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="adomx-card" style="margin-bottom: 20px;">
            <div class="adomx-card-header">
                <h3>Course Details</h3>
            </div>
            <div class="adomx-card-body">
                <div style="margin-bottom: 15px;">
                    <strong>Teacher:</strong>
                    <div style="margin-top: 5px;">
                        {{ $course->teacher->name ?? 'N/A' }}
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Category:</strong>
                    <div style="margin-top: 5px;">
                        {{ $course->category->name ?? 'Uncategorized' }}
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Level:</strong>
                    <div style="margin-top: 5px;">
                        <span class="adomx-status-badge adomx-status-{{ $course->level ?? 'beginner' }}">
                            {{ ucfirst($course->level ?? 'Beginner') }}
                        </span>
                    </div>
                </div>
                @if($course->duration)
                    <div style="margin-bottom: 15px;">
                        <strong>Duration:</strong>
                        <div style="margin-top: 5px;">
                            {{ $course->duration }} hours
                        </div>
                    </div>
                @endif
                <div style="margin-bottom: 15px;">
                    <strong>Lessons:</strong>
                    <div style="margin-top: 5px;">
                        {{ $course->lessons->count() }} lessons
                    </div>
                </div>
                @if($course->reviews->count() > 0)
                    <div>
                        <strong>Rating:</strong>
                        <div style="margin-top: 5px; display: flex; align-items: center; gap: 10px;">
                            @php
                                $avgRating = $course->reviews->avg('rating');
                            @endphp
                            <div style="color: #fbbf24;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= round($avgRating) ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <span style="color: var(--text-secondary);">
                                {{ number_format($avgRating, 1) }} ({{ $course->reviews->count() }} reviews)
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($course->skill_tags)
            <div class="adomx-card">
                <div class="adomx-card-header">
                    <h3>Skills You'll Gain</h3>
                </div>
                <div class="adomx-card-body">
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @foreach(explode(',', $course->skill_tags) as $tag)
                            <span style="background: var(--bg-secondary); padding: 5px 12px; border-radius: 4px; font-size: 13px; color: var(--text-primary);">
                                {{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Course Curriculum -->
@if($course->lessons->count() > 0)
    <div class="adomx-card" style="margin-bottom: 20px;">
        <div class="adomx-card-header">
            <h3>Course Curriculum</h3>
        </div>
        <div class="adomx-card-body">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($course->lessons->sortBy('order') as $index => $lesson)
                    <div style="padding: 15px; background: var(--bg-secondary); border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <strong>{{ $lesson->title }}</strong>
                                <div style="font-size: 13px; color: var(--text-secondary); margin-top: 3px;">
                                    <i class="fas fa-{{ $lesson->content_type == 'video' ? 'video' : 'file' }}"></i>
                                    {{ ucfirst($lesson->content_type ?? 'Lesson') }}
                                </div>
                            </div>
                        </div>
                        @if($isEnrolled)
                            <a href="{{ route('lessons.show', ['course' => $course->slug, 'lesson' => $lesson->id]) }}" class="adomx-btn adomx-btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-play"></i> Start
                            </a>
                        @else
                            <span style="color: var(--text-secondary); font-size: 13px;">
                                <i class="fas fa-lock"></i> Enroll to access
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Reviews Section -->
@if($course->reviews->count() > 0)
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Student Reviews</h3>
            <div style="display: flex; align-items: center; gap: 10px;">
                @php
                    $avgRating = $course->reviews->avg('rating');
                @endphp
                <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">
                    {{ number_format($avgRating, 1) }}
                </div>
                <div style="color: #fbbf24;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= round($avgRating) ? '' : '-o' }}"></i>
                    @endfor
                </div>
                <span style="color: var(--text-secondary);">
                    ({{ $course->reviews->count() }} reviews)
                </span>
            </div>
        </div>
        <div class="adomx-card-body">
            @foreach($course->reviews->take(10) as $review)
                <div style="padding: 20px; border-bottom: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                            <div style="display: flex; gap: 5px; margin: 5px 0;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#fbbf24' : '#e5e7eb' }};"></i>
                                @endfor
                            </div>
                        </div>
                        <small style="color: var(--text-secondary);">
                            {{ $review->created_at->format('M d, Y') }}
                        </small>
                    </div>
                    @if($review->comment)
                        <p style="color: var(--text-secondary); margin: 0; line-height: 1.6;">
                            {{ $review->comment }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection

