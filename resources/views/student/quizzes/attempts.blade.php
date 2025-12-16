@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Quiz Attempts</h2>
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
        <h3 class="adomx-table-title">All My Attempts</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Course</th>
                    <th>Score</th>
                    <th>Pass Score</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempts as $attempt)
                    <tr>
                        <td><strong>{{ $attempt->quiz->title ?? 'N/A' }}</strong></td>
                        <td>{{ $attempt->quiz->course->title ?? 'N/A' }}</td>
                        <td><strong>{{ $attempt->score }}%</strong></td>
                        <td>{{ $attempt->quiz->pass_score ?? 70 }}%</td>
                        <td>
                            @if($attempt->score >= ($attempt->quiz->pass_score ?? 70))
                                <span class="adomx-status-badge adomx-status-published">Passed</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">Failed</span>
                            @endif
                        </td>
                        <td>{{ $attempt->completed_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('student.quizzes.result', $attempt) }}" class="adomx-action-btn" title="View Result">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="adomx-table-empty">No quiz attempts yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $attempts->links() }}
    </div>
</div>
@endsection

