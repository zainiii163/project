@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Enrolled Courses</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('courses.index') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-search"></i>
            Browse Courses
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All My Courses</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('student.courses.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search courses..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Enrolled</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td>
                            <strong><a href="{{ route('student.courses.show', $course) }}" style="color: var(--text-primary); text-decoration: none;">{{ $course->title }}</a></strong>
                        </td>
                        <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                        <td>
                            <div class="adomx-progress-bar" style="margin-top: 0;">
                                <div class="adomx-progress-fill" style="width: {{ $course->pivot->progress ?? 0 }}%; background: var(--primary-color);">
                                    <span style="font-size: 11px; padding: 0 5px; color: white;">{{ $course->pivot->progress ?? 0 }}%</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($course->pivot->completed_at)
                                <span class="adomx-status-badge adomx-status-published">Completed</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">In Progress</span>
                            @endif
                        </td>
                        <td>{{ $course->pivot->enrolled_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('student.courses.show', $course) }}" class="adomx-btn adomx-btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-play"></i>
                                Continue
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">
                            No enrolled courses yet. <a href="{{ route('courses.index') }}" style="color: var(--primary-color);">Browse courses</a> to get started!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $courses->links() }}
    </div>
</div>
@endsection

