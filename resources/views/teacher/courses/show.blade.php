@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>{{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 24px; margin: 0; color: var(--primary-color);">{{ $course->students->count() }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Students</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 24px; margin: 0; color: var(--success-color);">{{ $course->lessons->count() }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Lessons</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 24px; margin: 0; color: var(--info-color);">{{ $course->quizzes->count() }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Quizzes</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 24px; margin: 0; color: var(--warning-color);">{{ $course->assignments->count() }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Assignments</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Course Information</h3>
        </div>
        <div class="adomx-card-body">
            <p><strong>Description:</strong> {{ $course->description ?? 'N/A' }}</p>
            <p><strong>Category:</strong> {{ $course->category->name ?? 'Uncategorized' }}</p>
            <p><strong>Price:</strong> ${{ number_format($course->price, 2) }}</p>
            <p><strong>Status:</strong> 
                <span class="adomx-status-badge adomx-status-{{ $course->status }}">
                    {{ ucfirst($course->status) }}
                </span>
            </p>
        </div>
    </div>

    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Quick Actions</h3>
        </div>
        <div class="adomx-card-body" style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('teacher.courses.students', $course) }}" class="adomx-btn adomx-btn-primary">
                <i class="fas fa-users"></i>
                View Students
            </a>
            <a href="{{ route('teacher.courses.performance', $course) }}" class="adomx-btn" style="background: var(--info-color);">
                <i class="fas fa-chart-line"></i>
                View Performance
            </a>
            <a href="{{ route('courses.edit', $course) }}" class="adomx-btn" style="background: var(--warning-color);">
                <i class="fas fa-edit"></i>
                Edit Course
            </a>
        </div>
    </div>
</div>

<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-header">
        <h3>Recent Reviews</h3>
    </div>
    <div class="adomx-card-body">
        @forelse($course->reviews->take(5) as $review)
            <div style="padding: 15px; border-bottom: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <strong>{{ $review->user->name }}</strong>
                        <div style="display: flex; gap: 5px; margin: 5px 0;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <p style="margin: 5px 0 0 0;">{{ $review->comment }}</p>
                    </div>
                    <small style="color: var(--text-secondary);">{{ $review->created_at->format('M d, Y') }}</small>
                </div>
            </div>
        @empty
            <p style="text-align: center; color: var(--text-secondary); padding: 20px;">No reviews yet</p>
        @endforelse
    </div>
</div>
@endsection

