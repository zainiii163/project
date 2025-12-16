@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>All My Courses</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.courses.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Course
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All My Courses</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('teacher.courses.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search courses..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Students</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td><strong>{{ $course->title }}</strong></td>
                        <td>{{ $course->category->name ?? 'Uncategorized' }}</td>
                        <td>{{ $course->students->count() }}</td>
                        <td>${{ number_format($course->price, 2) }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $course->status }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('teacher.courses.show', $course) }}" class="adomx-action-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('teacher.courses.students', $course) }}" class="adomx-action-btn" title="Students">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href="{{ route('teacher.courses.analytics', $course) }}" class="adomx-action-btn" title="Analytics">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <a href="{{ route('teacher.courses.edit', $course) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="adomx-table-empty">No courses found</td>
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

