@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Teacher Dashboard</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
</div>

<div class="adomx-row">
    <!-- Statistics Cards -->
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Courses</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">{{ $stats['total_courses'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(79, 70, 229, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book" style="font-size: 24px; color: var(--primary-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Published Courses</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">{{ $stats['published_courses'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="font-size: 24px; color: var(--success-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Students</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--info-color);">{{ $stats['total_students'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users" style="font-size: 24px; color: var(--info-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Enrollments</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--warning-color);">{{ $stats['total_enrollments'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-graduate" style="font-size: 24px; color: var(--warning-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>My Recent Courses</h3>
                <a href="{{ route('teacher.courses.index') }}" class="adomx-btn adomx-btn-primary" style="padding: 8px 15px; font-size: 14px;">
                    View All
                </a>
            </div>
            <div class="adomx-card-body">
                @if($courses->count() > 0)
                    <div class="adomx-table-container">
                        <table class="adomx-table">
                            <thead>
                                <tr>
                                    <th>Course Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td><strong>{{ $course->title }}</strong></td>
                                        <td>{{ $course->category->name ?? 'Uncategorized' }}</td>
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
                                                <a href="{{ route('teacher.courses.edit', $course) }}" class="adomx-action-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('teacher.courses.analytics', $course) }}" class="adomx-action-btn" title="Analytics" style="color: var(--info-color);">
                                                    <i class="fas fa-chart-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-book-open" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;"></i>
                        <h3 style="margin-bottom: 10px;">No Courses Yet</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 20px;">Start creating your first course to begin teaching!</p>
                        <a href="{{ route('teacher.courses.create') }}" class="adomx-btn adomx-btn-primary">
                            <i class="fas fa-plus"></i> Create Your First Course
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Performance Metrics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">${{ number_format($revenue, 2) }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Total Revenue</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">{{ number_format($avgRating, 1) }} ⭐</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Avg Rating</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">{{ $recentEnrollments }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Recent Enrollments (30d)</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">{{ $stats['published_courses'] }}/{{ $stats['total_courses'] }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Published/Total</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Performance Chart -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Enrollment Trend (Last 6 Months)</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="performanceChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="{{ route('teacher.courses.create') }}" class="adomx-btn adomx-btn-primary" style="padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-plus-circle" style="font-size: 32px;"></i>
                        <span>Create Course</span>
                    </a>
                    <a href="{{ route('teacher.lessons.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-video" style="font-size: 32px;"></i>
                        <span>Manage Lessons</span>
                    </a>
                    <a href="{{ route('teacher.quizzes.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-question-circle" style="font-size: 32px;"></i>
                        <span>Manage Quizzes</span>
                    </a>
                    <a href="{{ route('teacher.assignments.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-tasks" style="font-size: 32px;"></i>
                        <span>Manage Assignments</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Performance Chart (Enrollments)
    const performanceCtx = document.getElementById('performanceChart');
    if (performanceCtx) {
        const performanceData = @json($performanceData);
        new Chart(performanceCtx, {
            type: 'bar',
            data: {
                labels: performanceData.map(item => item.month),
                datasets: [{
                    label: 'Enrollments',
                    data: performanceData.map(item => item.enrollments),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection

