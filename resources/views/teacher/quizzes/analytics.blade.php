@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Quiz Analytics</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">{{ $quiz->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Quiz
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Statistics Cards -->
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Attempts</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">{{ $analytics['total_attempts'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(79, 70, 229, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clipboard-list" style="font-size: 24px; color: var(--primary-color);"></i>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Unique Students</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">{{ $analytics['unique_students'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(34, 197, 94, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users" style="font-size: 24px; color: var(--success-color);"></i>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Average Score</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--info-color);">{{ $analytics['average_score'] }}%</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chart-line" style="font-size: 24px; color: var(--info-color);"></i>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Pass Rate</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--warning-color);">{{ $analytics['pass_rate'] }}%</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(251, 191, 36, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-trophy" style="font-size: 24px; color: var(--warning-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Question Analysis</h3>
    </div>
    <div class="adomx-card-body">
        @if(count($analytics['question_analysis']) > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Error Rate</th>
                            <th>Difficulty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analytics['question_analysis'] as $analysis)
                            <tr>
                                <td>{{ Str::limit($analysis['question']->question_text ?? 'N/A', 100) }}</td>
                                <td>
                                    <span class="adomx-status-badge {{ $analysis['error_rate'] > 50 ? 'adomx-status-draft' : ($analysis['error_rate'] > 25 ? 'adomx-status-pending' : 'adomx-status-published') }}">
                                        {{ $analysis['error_rate'] }} errors
                                    </span>
                                </td>
                                <td>
                                    @if($analysis['error_rate'] > 50)
                                        <span style="color: var(--error-color);">Hard</span>
                                    @elseif($analysis['error_rate'] > 25)
                                        <span style="color: var(--warning-color);">Medium</span>
                                    @else
                                        <span style="color: var(--success-color);">Easy</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="color: var(--text-secondary);">No question analysis available yet.</p>
        @endif
    </div>
</div>

<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Recent Attempts</h3>
    </div>
    <div class="adomx-card-body">
        @if($quiz->attempts->count() > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Score</th>
                            <th>Submitted At</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quiz->attempts->take(10) as $attempt)
                            <tr>
                                <td><strong>{{ $attempt->user->name ?? 'N/A' }}</strong></td>
                                <td>{{ $attempt->score }}%</td>
                                <td>{{ $attempt->submitted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($attempt->score >= ($quiz->pass_score ?? 60))
                                        <span class="adomx-status-badge adomx-status-published">Passed</span>
                                    @else
                                        <span class="adomx-status-badge adomx-status-draft">Failed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="color: var(--text-secondary);">No attempts yet.</p>
        @endif
    </div>
</div>
@endsection

