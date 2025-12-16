@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Assignment Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.assignments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
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
                <strong>Grade:</strong> {{ $assignment->grade ?? 'Not graded yet' }}
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <strong>Your Submission:</strong>
            <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px;">
                {{ $assignment->content ?? 'No content submitted' }}
            </div>
        </div>

        @if($assignment->file_path)
            <div style="margin-bottom: 30px;">
                <strong>Uploaded File:</strong>
                <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="adomx-btn" style="background: var(--info-color); margin-top: 10px;">
                    <i class="fas fa-download"></i>
                    Download File
                </a>
            </div>
        @endif

        @if($assignment->grade)
            <div style="padding: 15px; background: rgba(16, 185, 129, 0.1); border-radius: 8px; border-left: 3px solid var(--success-color);">
                <strong>Grade:</strong> {{ $assignment->grade }}
            </div>
        @endif
    </div>
</div>
@endsection

