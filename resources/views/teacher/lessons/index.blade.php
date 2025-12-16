@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Lessons</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All My Lessons</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('teacher.lessons.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search lessons..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="course_id" class="adomx-search-input" style="max-width: 200px;">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Course</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Order</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lessons as $lesson)
                    <tr>
                        <td><strong>{{ $lesson->title }}</strong></td>
                        <td>{{ $lesson->course->title ?? 'N/A' }}</td>
                        <td>{{ ucfirst($lesson->content_type ?? 'text') }}</td>
                        <td>{{ $lesson->duration ? $lesson->duration . ' min' : 'N/A' }}</td>
                        <td>{{ $lesson->order ?? '-' }}</td>
                        <td>{{ $lesson->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No lessons found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $lessons->links() }}
    </div>
</div>
@endsection

