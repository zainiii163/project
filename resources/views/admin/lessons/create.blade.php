@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Lesson</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.lessons.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('admin.lessons.store') }}" method="POST">
            @csrf

            <div class="adomx-form-group">
                <label for="course_id" class="adomx-label">Course <span class="text-danger">*</span></label>
                <select id="course_id" name="course_id" class="adomx-input @error('course_id') is-invalid @enderror" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                <input type="text" id="title" name="title" class="adomx-input @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="description" class="adomx-label">Description</label>
                <textarea id="description" name="description" class="adomx-input @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="content_type" class="adomx-label">Content Type <span class="text-danger">*</span></label>
                    <select id="content_type" name="content_type" class="adomx-input @error('content_type') is-invalid @enderror" required>
                        <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="text" {{ old('content_type') == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="pdf" {{ old('content_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="link" {{ old('content_type') == 'link' ? 'selected' : '' }}>Link</option>
                    </select>
                    @error('content_type')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="duration" class="adomx-label">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" class="adomx-input @error('duration') is-invalid @enderror" value="{{ old('duration') }}" min="0">
                    @error('duration')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="video_url" class="adomx-label">Video URL</label>
                    <input type="url" id="video_url" name="video_url" class="adomx-input @error('video_url') is-invalid @enderror" value="{{ old('video_url') }}">
                    @error('video_url')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="content_url" class="adomx-label">Content URL</label>
                    <input type="text" id="content_url" name="content_url" class="adomx-input @error('content_url') is-invalid @enderror" value="{{ old('content_url') }}">
                    @error('content_url')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="order" class="adomx-label">Order</label>
                    <input type="number" id="order" name="order" class="adomx-input @error('order') is-invalid @enderror" value="{{ old('order') }}" min="0">
                    @error('order')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="is_free" class="adomx-label">Free Lesson</label>
                    <select id="is_free" name="is_free" class="adomx-input @error('is_free') is-invalid @enderror">
                        <option value="0" {{ old('is_free') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_free') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                    @error('is_free')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Lesson
                </button>
                <a href="{{ route('admin.lessons.index') }}" class="adomx-btn adomx-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

