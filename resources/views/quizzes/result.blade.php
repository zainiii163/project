@extends('layouts.main')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Quiz Result</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">{{ $attempt->quiz->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('quizzes.show', $attempt->quiz) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Quiz
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
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Question Review</h3>
    </div>
    <div class="adomx-card-body">
        @foreach($attempt->quiz->questions as $index => $question)
            @php
                $answer = $attempt->answers->where('question_id', $question->id)->first();
                $isCorrect = $answer && $answer->is_correct;
            @endphp
            <div style="margin-bottom: 30px; padding: 20px; border: 1px solid var(--border-color); border-radius: 8px; background: {{ $isCorrect ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' }};">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <h4 style="margin: 0;">
                        <span style="background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 4px; margin-right: 10px;">
                            {{ $index + 1 }}
                        </span>
                        {{ $question->question }}
                    </h4>
                    @if($isCorrect)
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
                    @if($answer)
                        <div style="margin-bottom: 10px;">
                            <strong>Your Answer:</strong>
                            <span style="color: {{ $isCorrect ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                @if($answer->option)
                                    {{ $answer->option->option_text }}
                                @else
                                    {{ $answer->answer_text ?? 'N/A' }}
                                @endif
                            </span>
                        </div>
                    @endif
                    
                    @if(!$isCorrect)
                        <div>
                            <strong>Correct Answer:</strong>
                            <span style="color: var(--success-color);">
                                @php
                                    $correctOption = $question->options->where('is_correct', true)->first();
                                @endphp
                                {{ $correctOption->option_text ?? 'N/A' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
    <a href="{{ route('quizzes.show', $attempt->quiz) }}" class="adomx-btn adomx-btn-primary">
        <i class="fas fa-list"></i>
        Back to Quiz
    </a>
    @if(!$passed && (!$attempt->quiz->max_attempts || $attempt->quiz->attempts->where('user_id', auth()->id())->count() < $attempt->quiz->max_attempts))
        <form action="{{ route('quizzes.attempt', $attempt->quiz) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="adomx-btn adomx-btn-secondary">
                <i class="fas fa-redo"></i>
                Try Again
            </button>
        </form>
    @endif
</div>
@endsection

