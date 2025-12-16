@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create New Quiz</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Course: {{ $course->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Quizzes
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Quiz Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('teacher.quizzes.store', $course) }}" method="POST">
            @csrf

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Quiz Title <span class="adomx-required">*</span></label>
                <input type="text" class="adomx-form-input @error('title') adomx-input-error @enderror" name="title" value="{{ old('title') }}" required placeholder="Enter quiz title">
                @error('title')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Description</label>
                <textarea class="adomx-form-input @error('description') adomx-input-error @enderror" name="description" rows="4" placeholder="Describe what this quiz covers...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Duration (Minutes)</label>
                    <input type="number" min="1" class="adomx-form-input @error('duration') adomx-input-error @enderror" name="duration" value="{{ old('duration') }}" placeholder="e.g., 30">
                    <small class="adomx-form-hint">Leave empty for unlimited time</small>
                    @error('duration')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Max Attempts</label>
                    <input type="number" min="1" class="adomx-form-input @error('max_attempts') adomx-input-error @enderror" name="max_attempts" value="{{ old('max_attempts') }}" placeholder="e.g., 3">
                    <small class="adomx-form-hint">Leave empty for unlimited attempts</small>
                    @error('max_attempts')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Pass Score (%)</label>
                    <input type="number" step="0.01" min="0" max="100" class="adomx-form-input @error('pass_score') adomx-input-error @enderror" name="pass_score" value="{{ old('pass_score', 60) }}" placeholder="e.g., 60">
                    <small class="adomx-form-hint">Minimum score to pass (0-100)</small>
                    @error('pass_score')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Question Type <span class="adomx-required">*</span></label>
                    <select class="adomx-form-input @error('question_type') adomx-input-error @enderror" name="question_type" required>
                        <option value="mcq" {{ old('question_type') == 'mcq' ? 'selected' : '' }}>Multiple Choice (MCQ)</option>
                        <option value="essay" {{ old('question_type') == 'essay' ? 'selected' : '' }}>Essay</option>
                        <option value="coding" {{ old('question_type') == 'coding' ? 'selected' : '' }}>Coding</option>
                        <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                    </select>
                    @error('question_type')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} style="margin-right: 8px;">
                    Publish Quiz
                </label>
                <small class="adomx-form-hint">Published quizzes are immediately available to students</small>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Quiz
                </button>
                <a href="{{ route('teacher.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">AI-Assisted Quiz Generation</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('teacher.quizzes.ai-generate', $course) }}" method="POST">
            @csrf
            <p style="color: var(--text-secondary); margin-bottom: 20px;">Let AI help you generate quiz questions automatically based on a topic.</p>
            
            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Topic <span class="adomx-required">*</span></label>
                <input type="text" class="adomx-form-input" name="topic" value="{{ old('topic') }}" required placeholder="e.g., JavaScript Functions">
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Difficulty <span class="adomx-required">*</span></label>
                    <select class="adomx-form-input" name="difficulty" required>
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Number of Questions <span class="adomx-required">*</span></label>
                    <input type="number" min="1" max="50" class="adomx-form-input" name="num_questions" value="{{ old('num_questions', 10) }}" required>
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Question Type <span class="adomx-required">*</span></label>
                <select class="adomx-form-input" name="question_type" required>
                    <option value="mcq">Multiple Choice (MCQ)</option>
                    <option value="essay">Essay</option>
                    <option value="coding">Coding</option>
                </select>
            </div>

            <button type="submit" class="adomx-btn adomx-btn-primary">
                <i class="fas fa-magic"></i>
                Generate Quiz with AI
            </button>
        </form>
    </div>
</div>
@endsection

