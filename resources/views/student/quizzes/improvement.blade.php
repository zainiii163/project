@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Quiz Improvement Tracking</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Track your progress across all quizzes</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Quiz Performance Overview</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Course</th>
                    <th>Attempts</th>
                    <th>Average Score</th>
                    <th>Best Score</th>
                    <th>Improvement</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($improvements as $improvement)
                    @php
                        $quiz = $improvement['quiz'];
                        $passScore = $quiz->pass_score ?? 70;
                        $passed = $improvement['max_score'] >= $passScore;
                    @endphp
                    <tr>
                        <td><strong>{{ $quiz->title ?? 'N/A' }}</strong></td>
                        <td>{{ $quiz->course->title ?? 'N/A' }}</td>
                        <td>{{ $improvement['attempts'] }}</td>
                        <td>
                            <strong>{{ number_format($improvement['avg_score'], 1) }}%</strong>
                        </td>
                        <td>
                            <strong style="color: {{ $passed ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                {{ number_format($improvement['max_score'], 1) }}%
                            </strong>
                        </td>
                        <td>
                            @if($improvement['improvement'] === 'improving')
                                <span class="adomx-status-badge adomx-status-published">
                                    <i class="fas fa-arrow-up"></i> Improving
                                </span>
                            @elseif($improvement['improvement'] === 'declining')
                                <span class="adomx-status-badge adomx-status-draft">
                                    <i class="fas fa-arrow-down"></i> Declining
                                </span>
                            @else
                                <span class="adomx-status-badge" style="background: var(--text-secondary);">
                                    <i class="fas fa-minus"></i> Stable
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($passed)
                                <span class="adomx-status-badge adomx-status-published">Passed</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">Not Passed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="adomx-table-empty">
                            No quiz attempts yet. Start taking quizzes to track your improvement!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(count($improvements) > 0)
<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-header">
        <h3>Performance Insights</h3>
    </div>
    <div class="adomx-card-body">
        @php
            $totalQuizzes = count($improvements);
            $passedQuizzes = collect($improvements)->filter(function($item) {
                $passScore = $item['quiz']->pass_score ?? 70;
                return $item['max_score'] >= $passScore;
            })->count();
            $avgScore = collect($improvements)->avg('avg_score');
        @endphp
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px;">
                <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">
                    {{ $totalQuizzes }}
                </div>
                <div style="color: var(--text-secondary); margin-top: 5px;">Total Quizzes</div>
            </div>
            
            <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px;">
                <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">
                    {{ $passedQuizzes }}
                </div>
                <div style="color: var(--text-secondary); margin-top: 5px;">Passed Quizzes</div>
            </div>
            
            <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px;">
                <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">
                    {{ number_format($avgScore, 1) }}%
                </div>
                <div style="color: var(--text-secondary); margin-top: 5px;">Average Score</div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

