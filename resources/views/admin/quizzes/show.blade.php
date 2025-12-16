@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Quiz Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-edit"></i>
            Edit Quiz
        </a>
        <a href="{{ route('admin.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>{{ $quiz->title }}</h3>
    </div>
    <div class="adomx-card-body">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>Course:</strong> {{ $quiz->course->title ?? 'N/A' }}
            </div>
            <div>
                <strong>Pass Score:</strong> {{ $quiz->pass_score }}%
            </div>
            <div>
                <strong>Time Limit:</strong> {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'Unlimited' }}
            </div>
            <div>
                <strong>Max Attempts:</strong> {{ $quiz->max_attempts ?? 'Unlimited' }}
            </div>
        </div>

        @if($quiz->description)
            <div style="margin-bottom: 30px;">
                <strong>Description:</strong>
                <p>{{ $quiz->description }}</p>
            </div>
        @endif

        <h4 style="margin-bottom: 20px;">Questions ({{ $quiz->questions->count() }})</h4>
        
        @if($quiz->questions->count() > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Correct Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quiz->questions as $index => $question)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $question->text }}</td>
                                <td>{{ $question->options->count() }}</td>
                                <td>
                                    @foreach($question->options as $option)
                                        @if($option->is_correct)
                                            <span class="adomx-status-badge adomx-status-published">{{ $option->text }}</span>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No questions added yet.</p>
        @endif

        <h4 style="margin-top: 30px; margin-bottom: 20px;">Attempts ({{ $quiz->attempts->count() }})</h4>
        
        @if($quiz->attempts->count() > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Score</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quiz->attempts as $attempt)
                            <tr>
                                <td>{{ $attempt->user->name ?? 'N/A' }}</td>
                                <td>{{ $attempt->score }}%</td>
                                <td>{{ $attempt->submitted_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No attempts yet.</p>
        @endif
    </div>
</div>
@endsection

