@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>{{ $quiz->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>Course:</strong> {{ $quiz->course->title ?? 'N/A' }}
            </div>
            <div>
                <strong>Pass Score:</strong> {{ $quiz->pass_score }}%
            </div>
            <div>
                <strong>Questions:</strong> {{ $quiz->questions->count() }}
            </div>
            <div>
                <strong>Total Attempts:</strong> {{ $quiz->attempts->count() }}
            </div>
        </div>

        <h4 style="margin-bottom: 20px;">Quiz Attempts</h4>
        
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
                        @foreach($quiz->attempts as $attempt)
                            <tr>
                                <td>{{ $attempt->user->name ?? 'N/A' }}</td>
                                <td>{{ $attempt->score }}%</td>
                                <td>{{ $attempt->submitted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($attempt->score >= $quiz->pass_score)
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
            <p>No attempts yet.</p>
        @endif
    </div>
</div>
@endsection

