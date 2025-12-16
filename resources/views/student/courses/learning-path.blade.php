@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Learning Path</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to My Courses
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Skills Development -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Skills You're Developing</h3>
            </div>
            <div class="adomx-card-body">
                @if(count($skills) > 0)
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach($skills as $skill => $count)
                            <div style="padding: 10px 15px; background: var(--primary-color); color: white; border-radius: 20px; font-size: 14px;">
                                {{ $skill }} <span style="opacity: 0.8;">({{ $count }})</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: var(--text-secondary); text-align: center; padding: 20px;">
                        No skills tracked yet. Complete courses to build your skill profile!
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Your Enrolled Courses</h3>
            </div>
            <div class="adomx-card-body">
                @if($enrolledCourses->count() > 0)
                    <div class="adomx-table-container">
                        <table class="adomx-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrolledCourses as $course)
                                    <tr>
                                        <td>
                                            <a href="{{ route('student.courses.show', $course) }}" style="color: var(--primary-color);">
                                                {{ $course->title }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="adomx-progress-bar">
                                                <div class="adomx-progress-fill" style="width: {{ $course->pivot->progress ?? 0 }}%;"></div>
                                            </div>
                                            <span style="font-size: 12px;">{{ $course->pivot->progress ?? 0 }}%</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="color: var(--text-secondary); text-align: center; padding: 20px;">
                        You haven't enrolled in any courses yet.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Suggested Next Courses -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Suggested Next Courses</h3>
                <p style="color: var(--text-secondary); margin-top: 10px;">Based on your current skills and learning path</p>
            </div>
            <div class="adomx-card-body">
                @if($suggestedCourses->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                        @foreach($suggestedCourses as $course)
                            <div style="border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: var(--card-bg);">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width: 100%; height: 150px; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 150px; background: var(--dark-bg-light); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-book" style="font-size: 36px; color: var(--text-secondary);"></i>
                                    </div>
                                @endif
                                <div style="padding: 15px;">
                                    <h4 style="margin-bottom: 10px; font-size: 16px; font-weight: 600;">{{ $course->title }}</h4>
                                    <p style="color: var(--text-secondary); font-size: 13px; margin-bottom: 15px; min-height: 40px;">
                                        {{ Str::limit($course->description, 80) }}
                                    </p>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div style="font-size: 12px; color: var(--text-secondary);">
                                            {{ $course->students_count }} students
                                        </div>
                                        <div style="font-size: 18px; font-weight: bold; color: var(--primary-color);">
                                            ${{ number_format($course->price, 2) }}
                                        </div>
                                    </div>
                                    @if($course->slug)
                                        <a href="{{ route('courses.show', $course->slug) }}" class="adomx-btn adomx-btn-primary" style="width: 100%; text-align: center; margin-top: 15px;">
                                            <i class="fas fa-eye"></i> View Course
                                        </a>
                                    @else
                                        <a href="{{ route('student.courses.show', $course) }}" class="adomx-btn adomx-btn-primary" style="width: 100%; text-align: center; margin-top: 15px;">
                                            <i class="fas fa-eye"></i> View Course
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-route" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;"></i>
                        <h3 style="margin-bottom: 10px;">No Suggestions Available</h3>
                        <p style="color: var(--text-secondary);">Complete more courses to get personalized learning path suggestions!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

