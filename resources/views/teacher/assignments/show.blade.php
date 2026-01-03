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
            <div class="adomx-card" style="margin-top: 20px;">
                <div class="adomx-card-header">
                    <h3>Grade Assignment</h3>
                </div>
                <div class="adomx-card-body">
                    <form action="{{ route('teacher.assignments.grade', $assignment) }}" method="POST">
                        @csrf
                        
                        <div class="adomx-form-group">
                            <label for="evaluation_type" class="adomx-label">Evaluation Type <span class="text-danger">*</span></label>
                            <select id="evaluation_type" name="evaluation_type" class="adomx-form-control" required onchange="toggleEvaluationType()">
                                <option value="manual" {{ old('evaluation_type', 'manual') == 'manual' ? 'selected' : '' }}>Manual Evaluation</option>
                                <option value="automated" {{ old('evaluation_type') == 'automated' ? 'selected' : '' }}>Automated Evaluation</option>
                            </select>
                            <small class="form-text text-muted">
                                <strong>Manual:</strong> You grade the assignment yourself<br>
                                <strong>Automated:</strong> System automatically evaluates based on criteria
                            </small>
                        </div>

                        <div id="manual-evaluation" class="evaluation-section">
                            <div class="adomx-form-group">
                                <label for="grade" class="adomx-label">Grade (Letter) <span class="text-danger">*</span></label>
                                <select id="grade" name="grade" class="adomx-form-control" required>
                                    <option value="">Select Grade</option>
                                    <option value="A+">A+</option>
                                    <option value="A">A</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B">B</option>
                                    <option value="B-">B-</option>
                                    <option value="C+">C+</option>
                                    <option value="C">C</option>
                                    <option value="C-">C-</option>
                                    <option value="D">D</option>
                                    <option value="F">F</option>
                                </select>
                            </div>
                            <div class="adomx-form-group">
                                <label for="score" class="adomx-label">Score (0 - {{ $assignment->max_score }})</label>
                                <input type="number" id="score" name="score" class="adomx-form-control" min="0" max="{{ $assignment->max_score }}" step="0.01" value="{{ old('score') }}">
                            </div>
                        </div>

                        <div id="automated-evaluation" class="evaluation-section" style="display: none;">
                            <div class="adomx-form-group">
                                <label>
                                    <input type="checkbox" name="auto_evaluate" value="1" checked>
                                    Automatically calculate score based on submission criteria
                                </label>
                            </div>
                            <div class="adomx-form-group">
                                <label for="min_words" class="adomx-label">Minimum Words (for text submissions)</label>
                                <input type="number" id="min_words" name="min_words" class="adomx-form-control" min="0" value="{{ old('min_words', 100) }}">
                            </div>
                            <div class="adomx-alert adomx-alert-info">
                                <i class="fas fa-info-circle"></i>
                                Automated evaluation will check word count, keywords, and other criteria. You can still provide manual feedback below.
                            </div>
                        </div>

                        <div class="adomx-form-group">
                            <label for="feedback" class="adomx-label">Feedback</label>
                            <textarea id="feedback" name="feedback" class="adomx-form-control" rows="5" placeholder="Provide detailed feedback to help the student improve...">{{ old('feedback') }}</textarea>
                        </div>

                        <button type="submit" class="adomx-btn adomx-btn-primary">
                            <i class="fas fa-check"></i>
                            Grade Assignment
                        </button>
                    </form>
                </div>
            </div>
        @elseif($assignment->grade)
            <div class="adomx-card" style="margin-top: 20px;">
                <div class="adomx-card-header">
                    <h3>Grading Details</h3>
                </div>
                <div class="adomx-card-body">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                        <div>
                            <strong>Grade:</strong> {{ $assignment->grade }}
                        </div>
                        @if($assignment->score)
                        <div>
                            <strong>Score:</strong> {{ $assignment->score }} / {{ $assignment->max_score }}
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
                            <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px;">
                                {{ $assignment->feedback }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function toggleEvaluationType() {
    const evaluationType = document.getElementById('evaluation_type').value;
    const manualSection = document.getElementById('manual-evaluation');
    const automatedSection = document.getElementById('automated-evaluation');
    
    if (evaluationType === 'manual') {
        manualSection.style.display = 'block';
        automatedSection.style.display = 'none';
        document.getElementById('grade').required = true;
    } else {
        manualSection.style.display = 'none';
        automatedSection.style.display = 'block';
        document.getElementById('grade').required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleEvaluationType();
});
</script>
@endsection

