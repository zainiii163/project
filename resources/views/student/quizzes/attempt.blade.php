@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Take Quiz: {{ $quiz->title }}</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Course: {{ $quiz->course->title ?? 'N/A' }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card" style="margin-bottom: 20px;">
    <div class="adomx-card-body">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <strong>Pass Score:</strong> {{ $quiz->pass_score ?? 70 }}%
            </div>
            <div>
                <strong>Max Attempts:</strong> {{ $quiz->max_attempts ?? 'Unlimited' }}
            </div>
            <div>
                <strong>Your Attempts:</strong> {{ $attemptCount }}
            </div>
            <div>
                <strong>Time Limit:</strong> {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No limit' }}
            </div>
        </div>
    </div>
</div>

<form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" id="quiz-form">
    @csrf
    
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Quiz Questions</h3>
        </div>
        <div class="adomx-card-body">
            @forelse($quiz->questions as $index => $question)
                <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid var(--border-color);">
                    <div style="margin-bottom: 15px;">
                        <h4 style="margin-bottom: 10px;">
                            <span style="background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 4px; margin-right: 10px;">
                                {{ $index + 1 }}
                            </span>
                            {{ $question->question }}
                        </h4>
                        <small style="color: var(--text-secondary);">
                            Type: {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                        </small>
                    </div>

                    @if($question->type === 'mcq' || $question->type === 'true_false')
                        <div style="margin-left: 40px;">
                            @foreach($question->options as $option)
                                <div style="margin-bottom: 10px;">
                                    <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px; transition: all 0.3s;">
                                        <input 
                                            type="radio" 
                                            name="answers[{{ $question->id }}]" 
                                            value="{{ $option->id }}" 
                                            required
                                            style="margin-right: 10px;"
                                        >
                                        <span>{{ $option->option_text }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @elseif($question->type === 'essay')
                        <div style="margin-left: 40px;">
                            <textarea 
                                name="answers[{{ $question->id }}]" 
                                rows="5" 
                                class="adomx-input" 
                                placeholder="Type your answer here..."
                                required
                                style="width: 100%;"
                            ></textarea>
                        </div>
                    @endif
                </div>
            @empty
                <p class="adomx-table-empty">No questions available for this quiz.</p>
            @endforelse
        </div>
    </div>

    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('student.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-times"></i>
            Cancel
        </a>
        <button type="submit" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-paper-plane"></i>
            Submit Quiz
        </button>
    </div>
</form>

@if($quiz->time_limit)
<script>
    let timeLimit = {{ $quiz->time_limit }} * 60; // Convert to seconds
    let timer = setInterval(function() {
        timeLimit--;
        let minutes = Math.floor(timeLimit / 60);
        let seconds = timeLimit % 60;
        
        document.getElementById('timer').textContent = 
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        
        if (timeLimit <= 0) {
            clearInterval(timer);
            document.getElementById('quiz-form').submit();
        }
    }, 1000);
</script>
<div class="adomx-alert adomx-alert-warning" style="position: fixed; top: 80px; right: 20px; z-index: 1000;">
    <i class="fas fa-clock"></i>
    Time Remaining: <span id="timer">{{ floor($quiz->time_limit) }}:00</span>
</div>
@endif
@endsection

