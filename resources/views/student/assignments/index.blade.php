@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Assignments</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All My Assignments</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('student.assignments.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Graded</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Submitted</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    <tr>
                        <td><strong>{{ $assignment->course->title ?? 'N/A' }}</strong></td>
                        <td>
                            @if($assignment->submitted_at)
                                {{ $assignment->submitted_at->format('M d, Y') }}
                            @else
                                <span class="adomx-status-badge adomx-status-draft">Not Submitted</span>
                            @endif
                        </td>
                        <td>{{ $assignment->grade ?? '-' }}</td>
                        <td>
                            @if($assignment->grade)
                                <span class="adomx-status-badge adomx-status-published">Graded</span>
                            @elseif($assignment->submitted_at)
                                <span class="adomx-status-badge adomx-status-draft">Pending</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">Not Submitted</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('student.assignments.show', $assignment) }}" class="adomx-action-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="adomx-table-empty">No assignments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $assignments->links() }}
    </div>
</div>
@endsection

