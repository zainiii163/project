@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Q&A Forum</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">{{ $course->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <button type="button" class="adomx-btn adomx-btn-primary" onclick="document.getElementById('ask-question-form').style.display='block'">
            <i class="fas fa-question-circle"></i>
            Ask Question
        </button>
        <a href="{{ route('student.courses.show', $course) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Course
        </a>
    </div>
</div>

<div id="ask-question-form" style="display: none; margin-bottom: 20px;">
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Ask a Question</h3>
        </div>
        <div class="adomx-card-body">
            <form action="{{ route('student.community.ask-question', $course) }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label class="adomx-label">Your Question</label>
                    <textarea name="question" class="adomx-input" rows="5" placeholder="What would you like to ask?" required></textarea>
                    <small style="color: var(--text-secondary);">Ask questions about the course content, assignments, or anything related to your learning.</small>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Submit Question
                    </button>
                    <button type="button" class="adomx-btn adomx-btn-secondary" onclick="document.getElementById('ask-question-form').style.display='none'">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Questions & Answers</h3>
    </div>
    <div class="adomx-card-body">
        @forelse($questions as $question)
            <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 10px;">{{ $question->question ?? 'Question' }}</h4>
                        <div style="display: flex; align-items: center; gap: 15px; color: var(--text-secondary); font-size: 14px;">
                            <span>
                                <i class="fas fa-user"></i>
                                {{ $question->user->name ?? 'Student' }}
                            </span>
                            <span>
                                <i class="fas fa-clock"></i>
                                {{ $question->created_at->diffForHumans() ?? 'Recently' }}
                            </span>
                            @if($question->answer)
                                <span class="adomx-status-badge adomx-status-published">
                                    <i class="fas fa-check"></i> Answered
                                </span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($question->answer)
                    <div style="margin-top: 15px; padding: 15px; background: rgba(34, 197, 94, 0.1); border-left: 3px solid var(--success-color); border-radius: 4px;">
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                            <i class="fas fa-user-tie" style="color: var(--success-color); margin-right: 8px;"></i>
                            <strong>Teacher's Answer:</strong>
                        </div>
                        <div style="color: var(--text-color);">
                            {{ $question->answer }}
                        </div>
                        @if($question->answered_at)
                            <div style="margin-top: 10px; font-size: 12px; color: var(--text-secondary);">
                                Answered {{ $question->answered_at->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div style="margin-top: 15px; padding: 15px; background: rgba(251, 191, 36, 0.1); border-left: 3px solid var(--warning-color); border-radius: 4px;">
                        <i class="fas fa-info-circle" style="color: var(--warning-color); margin-right: 8px;"></i>
                        <span style="color: var(--text-secondary);">Waiting for teacher's response...</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="adomx-table-empty">
                No questions yet. Be the first to ask a question!
            </div>
        @endforelse
    </div>
</div>
@endsection

