@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Quiz</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group">
                <label for="course_id" class="adomx-label">Course <span class="text-danger">*</span></label>
                <select id="course_id" name="course_id" class="adomx-input @error('course_id') is-invalid @enderror" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $quiz->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="title" class="adomx-label">Title <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" class="adomx-input @error('title') is-invalid @enderror" value="{{ old('title', $quiz->title) }}" required>
                @error('title')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="description" class="adomx-label">Description</label>
                <textarea id="description" name="description" class="adomx-input @error('description') is-invalid @enderror" rows="4">{{ old('description', $quiz->description) }}</textarea>
                @error('description')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="pass_score" class="adomx-label">Pass Score (%) <span class="text-danger">*</span></label>
                    <input type="number" id="pass_score" name="pass_score" class="adomx-input @error('pass_score') is-invalid @enderror" value="{{ old('pass_score', $quiz->pass_score) }}" min="0" max="100" required>
                    @error('pass_score')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="time_limit" class="adomx-label">Time Limit (minutes)</label>
                    <input type="number" id="time_limit" name="time_limit" class="adomx-input @error('time_limit') is-invalid @enderror" value="{{ old('time_limit', $quiz->time_limit) }}" min="0">
                    @error('time_limit')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="max_attempts" class="adomx-label">Max Attempts</label>
                    <input type="number" id="max_attempts" name="max_attempts" class="adomx-input @error('max_attempts') is-invalid @enderror" value="{{ old('max_attempts', $quiz->max_attempts) }}" min="0">
                    @error('max_attempts')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Quiz
                </button>
                <a href="{{ route('admin.quizzes.index') }}" class="adomx-btn adomx-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

