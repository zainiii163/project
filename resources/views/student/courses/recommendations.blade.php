@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Course Recommendations</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to My Courses
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Recommended Courses for You</h3>
        <p style="color: var(--text-secondary); margin-top: 10px;">Based on your learning history and interests</p>
    </div>
    <div class="adomx-card-body">
        @if($recommendations->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                @foreach($recommendations as $course)
                    <div style="border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: var(--card-bg);">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 200px; background: var(--dark-bg-light); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-book" style="font-size: 48px; color: var(--text-secondary);"></i>
                            </div>
                        @endif
                        <div style="padding: 20px;">
                            <h4 style="margin-bottom: 10px; font-size: 18px; font-weight: 600;">{{ $course->title }}</h4>
                            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 15px; min-height: 40px;">
                                {{ Str::limit($course->description, 100) }}
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                                        @if($course->reviews_avg_rating)
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star" style="color: {{ $i <= round($course->reviews_avg_rating) ? '#fbbf24' : '#d1d5db' }}; font-size: 12px;"></i>
                                            @endfor
                                            <span style="font-size: 12px; color: var(--text-secondary); margin-left: 5px;">
                                                ({{ number_format($course->reviews_avg_rating, 1) }})
                                            </span>
                                        @else
                                            <span style="font-size: 12px; color: var(--text-secondary);">No ratings yet</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">
                                        {{ $course->students_count }} students enrolled
                                    </div>
                                </div>
                                <div style="font-size: 20px; font-weight: bold; color: var(--primary-color);">
                                    ${{ number_format($course->price, 2) }}
                                </div>
                            </div>
                            @if($course->slug)
                                <a href="{{ route('courses.show', $course->slug) }}" class="adomx-btn adomx-btn-primary" style="width: 100%; text-align: center;">
                                    <i class="fas fa-eye"></i> View Course
                                </a>
                            @else
                                <a href="{{ route('student.courses.show', $course) }}" class="adomx-btn adomx-btn-primary" style="width: 100%; text-align: center;">
                                    <i class="fas fa-eye"></i> View Course
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-book-open" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">No Recommendations Available</h3>
                <p style="color: var(--text-secondary);">Start enrolling in courses to get personalized recommendations!</p>
                <a href="{{ route('courses.index') }}" class="adomx-btn adomx-btn-primary" style="margin-top: 20px;">
                    <i class="fas fa-search"></i> Browse Courses
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

