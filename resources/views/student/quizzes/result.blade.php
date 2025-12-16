@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Quiz Result</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">{{ $attempt->quiz->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Quizzes
        </a>
    </div>
</div>

<div class="adomx-card" style="margin-bottom: 20px;">
    <div class="adomx-card-body" style="text-align: center;">
        @php
            $passScore = $attempt->quiz->pass_score ?? 70;
            $passed = $attempt->score >= $passScore;
        @endphp
        
        <div style="margin-bottom: 20px;">
            @if($passed)
                <i class="fas fa-check-circle" style="font-size: 80px; color: var(--success-color);"></i>
            @else
                <i class="fas fa-times-circle" style="font-size: 80px; color: var(--danger-color);"></i>
            @endif
        </div>
        
        <h2 style="margin-bottom: 10px;">
            @if($passed)
                <span style="color: var(--success-color);">Congratulations! You Passed!</span>
            @else
                <span style="color: var(--danger-color);">You Did Not Pass</span>
            @endif
        </h2>
        
        <div style="margin: 30px 0;">
            <div style="font-size: 48px; font-weight: bold; color: var(--primary-color); margin-bottom: 10px;">
                {{ number_format($attempt->score, 1) }}%
            </div>
            <p style="color: var(--text-secondary);">
                Pass Score: {{ $passScore }}%
            </p>
        </div>

        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 30px; flex-wrap: wrap;">
            <div style="text-align: center;">
                <div style="font-size: 24px; font-weight: bold;">{{ count($results) }}</div>
                <div style="color: var(--text-secondary); font-size: 14px;">Total Questions</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 24px; font-weight: bold; color: var(--success-color);">
                    {{ collect($results)->where('is_correct', true)->count() }}
                </div>
                <div style="color: var(--text-secondary); font-size: 14px;">Correct</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 24px; font-weight: bold; color: var(--danger-color);">
                    {{ collect($results)->where('is_correct', false)->count() }}
                </div>
                <div style="color: var(--text-secondary); font-size: 14px;">Incorrect</div>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Question Review</h3>
    </div>
    <div class="adomx-card-body">
        @foreach($results as $index => $result)
            <div style="margin-bottom: 30px; padding: 20px; border: 1px solid var(--border-color); border-radius: 8px; background: {{ $result['is_correct'] ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' }};">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <h4 style="margin: 0;">
                        <span style="background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 4px; margin-right: 10px;">
                            {{ $index + 1 }}
                        </span>
                        {{ $result['question']->question }}
                    </h4>
                    @if($result['is_correct'])
                        <span class="adomx-status-badge adomx-status-published">
                            <i class="fas fa-check"></i> Correct
                        </span>
                    @else
                        <span class="adomx-status-badge adomx-status-draft">
                            <i class="fas fa-times"></i> Incorrect
                        </span>
                    @endif
                </div>

                <div style="margin-left: 40px;">
                    <div style="margin-bottom: 10px;">
                        <strong>Your Answer:</strong>
                        @if($result['student_answer'])
                            @php
                                $studentOption = $result['question']->options->where('id', $result['student_answer'])->first();
                            @endphp
                            <span style="color: {{ $result['is_correct'] ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                {{ $studentOption->option_text ?? 'N/A' }}
                            </span>
                        @else
                            <span style="color: var(--text-secondary);">No answer provided</span>
                        @endif
                    </div>
                    
                    @if(!$result['is_correct'] && $result['correct_answer'])
                        <div>
                            <strong>Correct Answer:</strong>
                            <span style="color: var(--success-color);">
                                {{ $result['correct_answer']->option_text }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
    <a href="{{ route('student.quizzes.index') }}" class="adomx-btn adomx-btn-primary">
        <i class="fas fa-list"></i>
        Back to Quizzes
    </a>
    @if(!$passed && (!$attempt->quiz->max_attempts || $attempt->quiz->attempts->count() < $attempt->quiz->max_attempts))
        <a href="{{ route('student.quizzes.attempt', $attempt->quiz) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-redo"></i>
            Try Again
        </a>
    @endif
</div>
@endsection

