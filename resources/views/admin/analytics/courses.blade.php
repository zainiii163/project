@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Course Analytics</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.analytics.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Analytics
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Course Performance</h3>
    </div>
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Category</th>
                    <th>Enrollments</th>
                    <th>Reviews</th>
                    <th>Avg Rating</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td><strong>{{ $course->title }}</strong></td>
                        <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                        <td>{{ $course->category->name ?? 'Uncategorized' }}</td>
                        <td>{{ $course->students_count }}</td>
                        <td>{{ $course->reviews_count }}</td>
                        <td>
                            @if($course->reviews_avg_rating)
                                {{ number_format($course->reviews_avg_rating, 1) }}/5
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $course->status }}">
                                {{ ucfirst($course->status) }}
                            </span>
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

