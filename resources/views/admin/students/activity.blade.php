@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Student Activity - {{ $student->name }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.students.show', $student) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Activity Logs -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Activity Logs</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>Date</th>
                                <th>IP Address</th>
                                <th>User Agent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityLogs as $log)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $log->action)) }}</td>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                    <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $log->user_agent }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="adomx-table-empty">No activity logs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="padding: 20px; border-top: 1px solid var(--border-color);">
                    {{ $activityLogs->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Course Progress -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Course Progress</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Category</th>
                                <th>Enrolled</th>
                                <th>Progress</th>
                                <th>Lessons Completed</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courseProgress as $progress)
                                <tr>
                                    <td><strong>{{ $progress['course']->title }}</strong></td>
                                    <td>{{ $progress['course']->teacher->name ?? 'N/A' }}</td>
                                    <td>{{ $progress['course']->category->name ?? 'Uncategorized' }}</td>
                                    <td>{{ $progress['enrolled_at'] ? $progress['enrolled_at']->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="adomx-progress-bar">
                                            <div class="adomx-progress-fill" style="width: {{ $progress['progress'] }}%;"></div>
                                        </div>
                                        <span>{{ $progress['progress'] }}%</span>
                                    </td>
                                    <td>{{ $progress['completed_lessons'] }} / {{ $progress['total_lessons'] }}</td>
                                    <td>{{ $progress['completed_at'] ? $progress['completed_at']->format('M d, Y') : 'In Progress' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="adomx-table-empty">No course progress found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

