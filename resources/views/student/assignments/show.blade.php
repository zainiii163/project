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

        @if(!$assignment->submitted_at)
            <div class="adomx-card" style="margin-top: 20px;">
                <div class="adomx-card-header">
                    <h3>Submit Assignment</h3>
                </div>
                <div class="adomx-card-body">
                    <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @if($assignment->submission_type === 'text')
                            <div class="adomx-form-group">
                                <label for="content" class="adomx-label">Your Answer <span class="text-danger">*</span></label>
                                <textarea id="content" name="content" class="adomx-form-control" rows="10" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif($assignment->submission_type === 'file')
                            <div class="adomx-form-group">
                                <label for="file" class="adomx-label">Upload File <span class="text-danger">*</span></label>
                                <input type="file" id="file" name="file" class="adomx-form-control" accept=".pdf,.doc,.docx,.zip,.txt" required>
                                <small>Accepted formats: PDF, DOC, DOCX, ZIP, TXT (Max: 10MB)</small>
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        @if($assignment->due_date)
                            <div class="adomx-alert adomx-alert-info" style="margin-bottom: 15px;">
                                <i class="fas fa-calendar"></i>
                                <strong>Due Date:</strong> {{ $assignment->due_date->format('M d, Y H:i') }}
                                @if($assignment->due_date->isPast())
                                    <span class="text-danger">(Overdue)</span>
                                @endif
                            </div>
                        @endif

                        <button type="submit" class="adomx-btn adomx-btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Submit Assignment
                        </button>
                    </form>
                </div>
            </div>
        @endif

        @if($assignment->grade)
            <div class="adomx-card" style="margin-top: 20px;">
                <div class="adomx-card-header">
                    <h3>Grading Results</h3>
                </div>
                <div class="adomx-card-body">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                        <div>
                            <strong>Grade:</strong> 
                            <span style="font-size: 24px; font-weight: bold; color: var(--primary-color);">{{ $assignment->grade }}</span>
                        </div>
                        @if($assignment->score)
                        <div>
                            <strong>Score:</strong> 
                            <span style="font-size: 24px; font-weight: bold; color: var(--success-color);">
                                {{ $assignment->score }} / {{ $assignment->max_score }}
                            </span>
                        </div>
                        @endif
                        @if($assignment->evaluation_type)
                        <div>
                            <strong>Evaluation Type:</strong> 
                            <span class="adomx-status-badge adomx-status-{{ $assignment->evaluation_type }}">
                                {{ ucfirst($assignment->evaluation_type) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @if($assignment->feedback)
                        <div>
                            <strong>Feedback:</strong>
                            <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px; border-left: 3px solid var(--primary-color);">
                                {{ $assignment->feedback }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

