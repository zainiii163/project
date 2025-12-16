@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Course Moderation - {{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Course Information -->
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Course Details</h3>
            </div>
            <div class="adomx-card-body">
                <table class="adomx-info-table">
                    <tr>
                        <td><strong>Title:</strong></td>
                        <td>{{ $course->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>Teacher:</strong></td>
                        <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $course->status }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Price:</strong></td>
                        <td>${{ number_format($course->price, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Students:</strong></td>
                        <td>{{ $course->students->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lessons:</strong></td>
                        <td>{{ $course->lessons->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Quizzes:</strong></td>
                        <td>{{ $course->quizzes->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Reviews:</strong></td>
                        <td>{{ $course->reviews->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Moderation Actions -->
        <div class="adomx-card" style="margin-top: 20px;">
            <div class="adomx-card-header">
                <h3>Moderation Actions</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <form action="{{ route('admin.courses.approve', $course) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="adomx-btn adomx-btn-success">
                            <i class="fas fa-check"></i> Approve Course
                        </button>
                    </form>
                    <button type="button" class="adomx-btn adomx-btn-danger" onclick="showRejectForm()">
                        <i class="fas fa-times"></i> Reject Course
                    </button>
                    <a href="{{ route('admin.courses.quality-check', $course) }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary);">
                        <i class="fas fa-check-circle"></i> Quality Check
                    </a>
                </div>

                <!-- Reject Form -->
                <div id="reject-form" style="display: none; margin-top: 20px; padding: 20px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px;">
                    <form action="{{ route('admin.courses.reject', $course) }}" method="POST">
                        @csrf
                        <div class="adomx-form-group" style="margin-bottom: 15px;">
                            <label class="adomx-form-label">Rejection Reason</label>
                            <textarea name="rejection_reason" class="adomx-input" rows="4" placeholder="Please provide a reason for rejection..."></textarea>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="adomx-btn adomx-btn-danger">
                                <i class="fas fa-times"></i> Reject Course
                            </button>
                            <button type="button" class="adomx-btn adomx-btn-secondary" onclick="hideRejectForm()">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content Summary -->
    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Content Summary</h3>
            </div>
            <div class="adomx-card-body">
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Lessons</div>
                    <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">{{ $course->lessons->count() }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Quizzes</div>
                    <div style="font-size: 24px; font-weight: bold; color: var(--info-color);">{{ $course->quizzes->count() }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Assignments</div>
                    <div style="font-size: 24px; font-weight: bold; color: var(--warning-color);">{{ $course->assignments->count() }}</div>
                </div>
                <div>
                    <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Enrolled Students</div>
                    <div style="font-size: 24px; font-weight: bold; color: var(--success-color);">{{ $course->students->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showRejectForm() {
    document.getElementById('reject-form').style.display = 'block';
}

function hideRejectForm() {
    document.getElementById('reject-form').style.display = 'none';
}
</script>

<style>
.adomx-info-table {
    width: 100%;
}

.adomx-info-table td {
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
}

.adomx-info-table td:first-child {
    width: 40%;
    color: var(--text-secondary);
}
</style>
@endsection

