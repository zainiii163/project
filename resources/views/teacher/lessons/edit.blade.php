@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Lesson</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.lessons.index', ['course_id' => $lesson->course_id]) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('teacher.lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="adomx-form-group">
                <label>Course <span class="text-danger">*</span></label>
                <select name="course_id" class="adomx-form-control" required>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $lesson->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label>Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="adomx-form-control" value="{{ old('title', $lesson->title) }}" required>
                @error('title')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label>Description</label>
                <textarea name="description" class="adomx-form-control" rows="4">{{ old('description', $lesson->description) }}</textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-row">
                <div class="adomx-col-md-6">
                    <div class="adomx-form-group">
                        <label>Type <span class="text-danger">*</span></label>
                        <select name="type" class="adomx-form-control" required>
                            <option value="video" {{ old('type', $lesson->type) == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="text" {{ old('type', $lesson->type) == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="pdf" {{ old('type', $lesson->type) == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="file" {{ old('type', $lesson->type) == 'file' ? 'selected' : '' }}>File</option>
                            <option value="quiz" {{ old('type', $lesson->type) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="adomx-col-md-6">
                    <div class="adomx-form-group">
                        <label>Duration (minutes)</label>
                        <input type="number" name="duration" class="adomx-form-control" value="{{ old('duration', $lesson->duration) }}" min="0">
                        @error('duration')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="adomx-form-group">
                <label>Video URL</label>
                <input type="url" name="video_url" class="adomx-form-control" value="{{ old('video_url', $lesson->content_url) }}" placeholder="https://youtube.com/watch?v=...">
                @error('video_url')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label>Or Upload Video File</label>
                <input type="file" name="video_file" class="adomx-form-control" accept="video/*">
                @error('video_file')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label>Or Upload Content File</label>
                <input type="file" name="content_file" class="adomx-form-control">
                @error('content_file')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label>Downloadable Materials (PDFs, Files)</label>
                <input type="file" name="downloadable_materials[]" class="adomx-form-control" multiple accept=".pdf,.doc,.docx,.zip">
                <small>You can select multiple files. Current files will be replaced.</small>
                @error('downloadable_materials')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-row">
                <div class="adomx-col-md-6">
                    <div class="adomx-form-group">
                        <label>Order</label>
                        <input type="number" name="order" class="adomx-form-control" value="{{ old('order', $lesson->order) }}" min="0">
                        @error('order')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="adomx-col-md-6">
                    <div class="adomx-form-group">
                        <label>
                            <input type="checkbox" name="is_preview" value="1" {{ old('is_preview', $lesson->is_preview) ? 'checked' : '' }}>
                            Make this lesson a preview (free to view)
                        </label>
                    </div>
                </div>
            </div>

            <div class="adomx-form-group">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i> Update Lesson
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

