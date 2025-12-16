@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Students - {{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.courses.show', $course) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Course
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Enrolled Students ({{ $students->total() }})</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Email</th>
                    <th>Enrolled</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Completed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td><strong>{{ $student->name }}</strong></td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->pivot->enrolled_at->format('M d, Y') }}</td>
                        <td>
                            <div class="adomx-progress-bar" style="margin-top: 0;">
                                <div class="adomx-progress-fill" style="width: {{ $student->pivot->progress ?? 0 }}%; background: var(--primary-color);">
                                    <span style="font-size: 11px; padding: 0 5px; color: white;">{{ $student->pivot->progress ?? 0 }}%</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($student->pivot->completed_at)
                                <span class="adomx-status-badge adomx-status-published">Completed</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">In Progress</span>
                            @endif
                        </td>
                        <td>
                            @if($student->pivot->completed_at)
                                {{ $student->pivot->completed_at->format('M d, Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No students enrolled yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $students->links() }}
    </div>
</div>
@endsection

