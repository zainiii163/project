@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Learning Dashboard</h1>
        <a href="{{ route('student.progress.index') }}" class="btn btn-secondary">
            <i class="fas fa-list"></i> View All Courses
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $stats['enrolled_courses'] }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Enrolled Courses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $stats['completed_courses'] }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $stats['total_lessons_completed'] }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Lessons Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $stats['certificates'] }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Certificates</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gamification Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ number_format($stats['xp_points']) }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">XP Points</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">Level {{ $stats['level'] }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Current Level</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $stats['badges'] }}</h3>
                    <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Badges Earned</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Learning Progress (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="progressChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Progress Breakdown -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Course Progress</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Progress</th>
                                    <th>Lessons</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseProgress as $data)
                                <tr>
                                    <td><strong>{{ $data['course']->title }}</strong></td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $data['progress'] }}%;" aria-valuenow="{{ $data['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ $data['progress'] }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $data['completed_lessons'] }}/{{ $data['total_lessons'] }}</td>
                                    <td>
                                        @if($data['progress'] == 100)
                                        <span class="badge badge-success">Completed</span>
                                        @else
                                        <span class="badge badge-info">In Progress</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('student.progress.course', $data['course']) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Performance -->
    @if($quizPerformance->isNotEmpty())
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quiz Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Quiz</th>
                                    <th>Course</th>
                                    <th>Attempts</th>
                                    <th>Best Score</th>
                                    <th>Average Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quizPerformance as $quiz)
                                <tr>
                                    <td>{{ $quiz->quiz->title ?? 'N/A' }}</td>
                                    <td>{{ $quiz->quiz->course->title ?? 'N/A' }}</td>
                                    <td>{{ $quiz->attempts }}</td>
                                    <td><strong>{{ number_format($quiz->max_score, 1) }}%</strong></td>
                                    <td>{{ number_format($quiz->avg_score, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($recentActivity as $activity)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>
                                        @if($activity['type'] === 'lesson_completed')
                                        <i class="fas fa-check-circle text-success"></i> Completed Lesson
                                        @else
                                        <i class="fas fa-question-circle text-info"></i> Completed Quiz
                                        @endif
                                    </strong>
                                    <p class="mb-0">{{ $activity['title'] }}</p>
                                    <small class="text-muted">{{ $activity['course'] }}</small>
                                    @if(isset($activity['score']))
                                    <small class="text-muted"> - Score: {{ $activity['score'] }}%</small>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            No recent activity.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const progressData = @json($progressChartData);
    const ctx = document.getElementById('progressChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: progressData.map(item => item.month),
                datasets: [{
                    label: 'Lessons Completed',
                    data: progressData.map(item => item.lessons),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Courses Completed',
                    data: progressData.map(item => item.courses),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endpush

