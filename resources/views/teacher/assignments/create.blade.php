@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create New Assignment</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Course: {{ $course->title }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.assignments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Assignments
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Assignment Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('teacher.assignments.store', $course) }}" method="POST">
            @csrf

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Assignment Title <span class="adomx-required">*</span></label>
                <input type="text" class="adomx-form-input @error('title') adomx-input-error @enderror" name="title" value="{{ old('title') }}" required placeholder="Enter assignment title">
                @error('title')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Description <span class="adomx-required">*</span></label>
                <textarea class="adomx-form-input @error('description') adomx-input-error @enderror" name="description" rows="5" required placeholder="Describe the assignment requirements...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Content/Instructions</label>
                <textarea class="adomx-form-input @error('content') adomx-input-error @enderror" name="content" rows="8" placeholder="Additional instructions, guidelines, or content for the assignment...">{{ old('content') }}</textarea>
                @error('content')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Due Date <span class="adomx-required">*</span></label>
                    <input type="datetime-local" class="adomx-form-input @error('due_date') adomx-input-error @enderror" name="due_date" value="{{ old('due_date') }}" required>
                    @error('due_date')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Max Score <span class="adomx-required">*</span></label>
                    <input type="number" step="0.01" min="0" class="adomx-form-input @error('max_score') adomx-input-error @enderror" name="max_score" value="{{ old('max_score', 100) }}" required>
                    @error('max_score')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Submission Type <span class="adomx-required">*</span></label>
                <select class="adomx-form-input @error('submission_type') adomx-input-error @enderror" name="submission_type" required>
                    <option value="file" {{ old('submission_type') == 'file' ? 'selected' : '' }}>File Upload</option>
                    <option value="text" {{ old('submission_type') == 'text' ? 'selected' : '' }}>Text Submission</option>
                    <option value="code" {{ old('submission_type') == 'code' ? 'selected' : '' }}>Code Submission</option>
                </select>
                <small class="adomx-form-hint">Choose how students will submit their work</small>
                @error('submission_type')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Assignment
                </button>
                <a href="{{ route('teacher.assignments.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

