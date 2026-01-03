@extends('layouts.main')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Assignment Details</h2>
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
                <strong>Course:</strong> {{ $assignment->course->title ?? 'N/A' }}
            </div>
            <div>
                <strong>Due Date:</strong> 
                @if($assignment->due_date)
                    {{ $assignment->due_date->format('M d, Y H:i') }}
                @else
                    No due date
                @endif
            </div>
            <div>
                <strong>Max Score:</strong> {{ $assignment->max_score }}
            </div>
            <div>
                <strong>Submission Type:</strong> {{ ucfirst($assignment->submission_type ?? 'text') }}
            </div>
        </div>

        @if($assignment->description)
            <div style="margin-bottom: 30px;">
                <strong>Description:</strong>
                <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px;">
                    {{ $assignment->description }}
                </div>
            </div>
        @endif

        @auth
            @php
                $userAssignment = $assignment->student_id == auth()->id() ? $assignment : null;
            @endphp

            @if($userAssignment)
                <div style="margin-bottom: 30px;">
                    <strong>Your Submission:</strong>
                    <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px;">
                        {{ $userAssignment->content ?? 'No content submitted' }}
                    </div>
                </div>

                @if($userAssignment->file_path)
                    <div style="margin-bottom: 30px;">
                        <strong>Uploaded File:</strong>
                        <a href="{{ asset('storage/' . $userAssignment->file_path) }}" target="_blank" class="adomx-btn" style="background: var(--info-color); margin-top: 10px;">
                            <i class="fas fa-download"></i>
                            Download File
                        </a>
                    </div>
                @endif

                @if($userAssignment->grade)
                    <div class="adomx-card" style="margin-top: 20px;">
                        <div class="adomx-card-header">
                            <h3>Grading Results</h3>
                        </div>
                        <div class="adomx-card-body">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                                <div>
                                    <strong>Grade:</strong> 
                                    <span style="font-size: 24px; font-weight: bold; color: var(--primary-color);">{{ $userAssignment->grade }}</span>
                                </div>
                                @if($userAssignment->score)
                                <div>
                                    <strong>Score:</strong> 
                                    <span style="font-size: 24px; font-weight: bold; color: var(--success-color);">
                                        {{ $userAssignment->score }} / {{ $userAssignment->max_score }}
                                    </span>
                                </div>
                                @endif
                                @if($userAssignment->evaluation_type)
                                <div>
                                    <strong>Evaluation Type:</strong> 
                                    <span class="adomx-status-badge adomx-status-{{ $userAssignment->evaluation_type }}">
                                        {{ ucfirst($userAssignment->evaluation_type) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            @if($userAssignment->feedback)
                                <div>
                                    <strong>Feedback:</strong>
                                    <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px; border-left: 3px solid var(--primary-color);">
                                        {{ $userAssignment->feedback }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="adomx-alert adomx-alert-info">
                    <i class="fas fa-info-circle"></i>
                    You haven't submitted this assignment yet.
                </div>
            @endif
        @else
            <div class="adomx-alert adomx-alert-info">
                <i class="fas fa-info-circle"></i>
                Please <a href="{{ route('login') }}">login</a> to view and submit this assignment.
            </div>
        @endauth
    </div>
</div>
@endsection

