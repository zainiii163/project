@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Struggling Students</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Course: {{ $course->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.courses.show', $course) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Course
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Students Needing Support</h3>
        <p style="color: var(--text-secondary); margin-top: 5px;">Students with low progress or poor quiz scores</p>
    </div>
    <div class="adomx-card-body">
        @if($strugglingStudents->count() > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Progress</th>
                            <th>Enrolled At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($strugglingStudents as $student)
                            <tr>
                                <td><strong>{{ $student->name }}</strong></td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="flex: 1; height: 8px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ $student->pivot->progress ?? 0 }}%; background: var(--warning-color);"></div>
                                        </div>
                                        <span style="font-size: 12px; color: var(--text-secondary);">{{ $student->pivot->progress ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td>{{ $student->pivot->enrolled_at ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if(($student->pivot->progress ?? 0) < 30)
                                        <span class="adomx-status-badge adomx-status-draft">Low Progress</span>
                                    @else
                                        <span class="adomx-status-badge adomx-status-pending">Poor Quiz Scores</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('teacher.courses.students', $course) }}" class="adomx-action-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="mailto:{{ $student->email }}" class="adomx-action-btn" title="Send Email" style="color: var(--info-color);">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px; padding: 15px; background: var(--bg-secondary); border-radius: 8px;">
                <h4 style="margin-bottom: 10px;">Recommended Actions:</h4>
                <ul style="margin: 0; padding-left: 20px; color: var(--text-secondary);">
                    <li>Reach out to these students with personalized support</li>
                    <li>Offer additional resources or one-on-one sessions</li>
                    <li>Review their quiz attempts to identify specific areas of difficulty</li>
                    <li>Consider creating supplementary materials for challenging topics</li>
                </ul>
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-check-circle" style="font-size: 48px; color: var(--success-color); margin-bottom: 15px;"></i>
                <h3 style="margin-bottom: 10px;">Great News!</h3>
                <p style="color: var(--text-secondary);">No struggling students found. All students are performing well in this course.</p>
            </div>
        @endif
    </div>
</div>
@endsection

