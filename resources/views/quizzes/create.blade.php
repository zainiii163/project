@extends('layouts.main')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create New Quiz</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Course: {{ $course->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ url()->previous() }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Quiz Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('quizzes.store', $course) }}" method="POST">
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
                    <label class="adomx-form-label">Max Attempts <span class="adomx-required">*</span></label>
                    <input type="number" min="1" class="adomx-form-input @error('max_attempts') adomx-input-error @enderror" name="max_attempts" value="{{ old('max_attempts', 1) }}" required placeholder="e.g., 3">
                    @error('max_attempts')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Pass Score (%) <span class="adomx-required">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" class="adomx-form-input @error('pass_score') adomx-input-error @enderror" name="pass_score" value="{{ old('pass_score', 60) }}" required placeholder="e.g., 60">
                    <small class="adomx-form-hint">Minimum score to pass (0-100)</small>
                    @error('pass_score')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} style="margin-right: 8px;">
                        Publish Quiz
                    </label>
                    <small class="adomx-form-hint">Published quizzes are immediately available to students</small>
                </div>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Quiz
                </button>
                <a href="{{ url()->previous() }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

