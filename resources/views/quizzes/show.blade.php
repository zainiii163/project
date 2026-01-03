@extends('layouts.main')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>{{ $quiz->title }}</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Course: {{ $quiz->course->title ?? 'N/A' }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ url()->previous() }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>Pass Score:</strong> {{ $quiz->pass_score ?? 70 }}%
            </div>
            <div>
                <strong>Time Limit:</strong> {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No limit' }}
            </div>
            <div>
                <strong>Max Attempts:</strong> {{ $quiz->max_attempts ?? 'Unlimited' }}
            </div>
            <div>
                <strong>Questions:</strong> {{ $quiz->questions->count() }}
            </div>
        </div>

        @if($quiz->description)
            <div style="margin-bottom: 30px;">
                <strong>Description:</strong>
                <p>{{ $quiz->description }}</p>
            </div>
        @endif

        @auth
            @php
                $userAttempts = $quiz->attempts()->where('user_id', auth()->id())->get();
                $canAttempt = !$quiz->max_attempts || $userAttempts->count() < $quiz->max_attempts;
            @endphp

            @if($canAttempt)
                <form action="{{ route('quizzes.attempt', $quiz) }}" method="POST">
                    @csrf
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-play"></i>
                        Start Quiz
                    </button>
                </form>
            @else
                <div class="adomx-alert adomx-alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    You have reached the maximum number of attempts for this quiz.
                </div>
            @endif

            @if($userAttempts->count() > 0)
                <div style="margin-top: 20px;">
                    <h4>Your Previous Attempts</h4>
                    <div class="adomx-table-container">
                        <table class="adomx-table">
                            <thead>
                                <tr>
                                    <th>Attempt</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userAttempts as $attempt)
                                    <tr>
                                        <td>#{{ $loop->iteration }}</td>
                                        <td>{{ $attempt->score }}%</td>
                                        <td>
                                            @if($attempt->score >= $quiz->pass_score)
                                                <span class="adomx-status-badge adomx-status-published">Passed</span>
                                            @else
                                                <span class="adomx-status-badge adomx-status-draft">Failed</span>
                                            @endif
                                        </td>
                                        <td>{{ $attempt->submitted_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('quizzes.result', $attempt) }}" class="adomx-btn adomx-btn-sm">
                                                View Result
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            <div class="adomx-alert adomx-alert-info">
                <i class="fas fa-info-circle"></i>
                Please <a href="{{ route('login') }}">login</a> to take this quiz.
            </div>
        @endauth
    </div>
</div>
@endsection

