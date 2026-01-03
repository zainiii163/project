@extends('layouts.main')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Quiz</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('quizzes.show', $quiz) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('quizzes.update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')

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
                    <label for="duration" class="adomx-label">Time Limit (minutes)</label>
                    <input type="number" id="duration" name="duration" class="adomx-input @error('duration') is-invalid @enderror" value="{{ old('duration', $quiz->time_limit) }}" min="0">
                    @error('duration')
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

            <div class="adomx-form-group">
                <label>
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $quiz->is_published) ? 'checked' : '' }} style="margin-right: 8px;">
                    Publish Quiz
                </label>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Quiz
                </button>
                <a href="{{ route('quizzes.show', $quiz) }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@if($quiz->questions->count() > 0)
<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-header">
        <h3>Questions ({{ $quiz->questions->count() }})</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Type</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quiz->questions as $index => $question)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ Str::limit($question->question, 50) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $question->type)) }}</td>
                            <td>{{ $question->options->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

