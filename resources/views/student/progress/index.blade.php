@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Learning Progress</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Course Progress Overview</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Lessons</th>
                    <th>Completed</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($progressData as $data)
                    <tr>
                        <td><strong>{{ $data['course']->title }}</strong></td>
                        <td>{{ $data['course']->teacher->name ?? 'N/A' }}</td>
                        <td>{{ $data['total_lessons'] }}</td>
                        <td>{{ $data['completed_lessons'] }}</td>
                        <td>
                            <div class="adomx-progress-bar" style="margin-top: 0;">
                                <div class="adomx-progress-fill" style="width: {{ $data['progress'] }}%; background: var(--primary-color);">
                                    <span style="font-size: 11px; padding: 0 5px; color: white;">{{ $data['progress'] }}%</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($data['progress'] == 100)
                                <span class="adomx-status-badge adomx-status-published">Completed</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">In Progress</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('student.courses.show', $data['course']) }}" class="adomx-btn adomx-btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-play"></i>
                                Continue
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="adomx-table-empty">
                            No enrolled courses yet. <a href="{{ route('courses.index') }}" style="color: var(--primary-color);">Browse courses</a> to get started!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

