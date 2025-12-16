@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Assignment Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.assignments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>Student:</strong> {{ $assignment->student->name ?? 'N/A' }}
            </div>
            <div>
                <strong>Course:</strong> {{ $assignment->course->title ?? 'N/A' }}
            </div>
            <div>
                <strong>Submitted:</strong> 
                @if($assignment->submitted_at)
                    {{ $assignment->submitted_at->format('M d, Y H:i') }}
                @else
                    Not submitted
                @endif
            </div>
            <div>
                <strong>Grade:</strong> {{ $assignment->grade ?? 'Not graded' }}
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <strong>Content:</strong>
            <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px;">
                {{ $assignment->content ?? 'No content' }}
            </div>
        </div>

        @if($assignment->file_path)
            <div style="margin-bottom: 30px;">
                <strong>File:</strong>
                <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="adomx-btn" style="background: var(--info-color); margin-top: 10px;">
                    <i class="fas fa-download"></i>
                    Download File
                </a>
            </div>
        @endif

        @if($assignment->submitted_at && !$assignment->grade)
            <form action="{{ route('teacher.assignments.grade', $assignment) }}" method="POST">
                @csrf
                <div class="adomx-form-group">
                    <label for="grade" class="adomx-label">Grade <span class="text-danger">*</span></label>
                    <input type="text" id="grade" name="grade" class="adomx-input" required>
                </div>
                <div class="adomx-form-group">
                    <label for="feedback" class="adomx-label">Feedback</label>
                    <textarea id="feedback" name="feedback" class="adomx-input" rows="4"></textarea>
                </div>
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-check"></i>
                    Grade Assignment
                </button>
            </form>
        @endif
    </div>
</div>
@endsection

