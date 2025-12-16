@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Course Analytics - {{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.courses.show', $course) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Enrollment Stats -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Enrollment Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Total Enrollments</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">{{ $analytics['enrollments']['total'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">
                            This Month: {{ $analytics['enrollments']['this_month'] }}
                        </div>
                    </div>
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Growth Rate</div>
                        <div style="font-size: 32px; font-weight: bold; color: {{ $analytics['enrollments']['growth_rate'] >= 0 ? 'var(--success-color)' : 'var(--danger-color)' }};">
                            {{ $analytics['enrollments']['growth_rate'] >= 0 ? '+' : '' }}{{ $analytics['enrollments']['growth_rate'] }}%
                        </div>
                    </div>
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Completion Rate</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">{{ $analytics['completion']['completion_rate'] }}%</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">
                            Completed: {{ $analytics['completion']['total_completed'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completion & Engagement -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Completion Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="padding: 20px;">
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span>Total Completed</span>
                            <strong>{{ $analytics['completion']['total_completed'] }}</strong>
                        </div>
                        <div class="adomx-progress-bar">
                            <div class="adomx-progress-fill" style="width: {{ $analytics['completion']['completion_rate'] }}%;"></div>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span>Average Progress</span>
                            <strong>{{ $analytics['completion']['avg_progress'] }}%</strong>
                        </div>
                        <div class="adomx-progress-bar">
                            <div class="adomx-progress-fill" style="width: {{ $analytics['completion']['avg_progress'] }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Engagement Stats -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Engagement Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="padding: 20px;">
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Average Lesson Views</div>
                        <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">{{ $analytics['engagement']['avg_lesson_views'] }}</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Quiz Attempts</div>
                        <div style="font-size: 24px; font-weight: bold; color: var(--info-color);">{{ $analytics['engagement']['quiz_attempts'] }}</div>
                    </div>
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Discussion Posts</div>
                        <div style="font-size: 24px; font-weight: bold; color: var(--success-color);">{{ $analytics['engagement']['discussion_posts'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ratings -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Ratings & Reviews</h3>
            </div>
            <div class="adomx-card-body">
                <div style="padding: 20px; text-align: center;">
                    <div style="font-size: 48px; font-weight: bold; color: var(--warning-color); margin-bottom: 10px;">
                        {{ $analytics['ratings']['average'] }}
                    </div>
                    <div style="margin-bottom: 20px;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="color: {{ $i <= round($analytics['ratings']['average']) ? '#fbbf24' : '#d1d5db' }}; font-size: 24px;"></i>
                        @endfor
                    </div>
                    <div style="font-size: 14px; color: var(--text-secondary);">
                        Based on {{ $analytics['ratings']['total'] }} reviews
                    </div>
                    <div style="margin-top: 20px; text-align: left;">
                        @foreach($analytics['ratings']['distribution'] as $rating => $count)
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <span style="width: 20px;">{{ $rating }}★</span>
                                <div class="adomx-progress-bar" style="flex: 1;">
                                    <div class="adomx-progress-fill" style="width: {{ $analytics['ratings']['total'] > 0 ? ($count / $analytics['ratings']['total'] * 100) : 0 }}%;"></div>
                                </div>
                                <span style="width: 40px; text-align: right;">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lesson Analytics -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Lesson Performance</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Lesson</th>
                                <th>Views</th>
                                <th>Completions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lessonAnalytics as $lesson)
                                <tr>
                                    <td>{{ $lesson['lesson']->title }}</td>
                                    <td>{{ $lesson['views'] }}</td>
                                    <td>{{ $lesson['completions'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="adomx-table-empty">No lessons found</td>
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

