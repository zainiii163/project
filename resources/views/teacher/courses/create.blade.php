@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create New Course</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Courses
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Course Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('teacher.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Course Title <span class="adomx-required">*</span></label>
                <input type="text" class="adomx-form-input @error('title') adomx-input-error @enderror" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Description</label>
                <textarea class="adomx-form-input @error('description') adomx-input-error @enderror" name="description" rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Category</label>
                    <select class="adomx-form-input @error('category_id') adomx-input-error @enderror" name="category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Level <span class="adomx-required">*</span></label>
                    <select class="adomx-form-input @error('level') adomx-input-error @enderror" name="level" required>
                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('level')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Price ($) <span class="adomx-required">*</span></label>
                    <input type="number" step="0.01" min="0" class="adomx-form-input @error('price') adomx-input-error @enderror" name="price" value="{{ old('price', 0) }}" required>
                    @error('price')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Duration (Hours)</label>
                    <input type="number" min="0" class="adomx-form-input @error('duration') adomx-input-error @enderror" name="duration" value="{{ old('duration') }}">
                    @error('duration')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Learning Objectives</label>
                <textarea class="adomx-form-input @error('objectives') adomx-input-error @enderror" name="objectives" rows="3" placeholder="What will students learn from this course?">{{ old('objectives') }}</textarea>
                @error('objectives')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Requirements</label>
                <textarea class="adomx-form-input @error('requirements') adomx-input-error @enderror" name="requirements" rows="3" placeholder="What are the prerequisites for this course?">{{ old('requirements') }}</textarea>
                @error('requirements')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Prerequisites</label>
                <textarea class="adomx-form-input @error('prerequisites') adomx-input-error @enderror" name="prerequisites" rows="2" placeholder="Any prerequisites?">{{ old('prerequisites') }}</textarea>
                @error('prerequisites')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Skill Tags</label>
                <input type="text" class="adomx-form-input @error('skill_tags') adomx-input-error @enderror" name="skill_tags" value="{{ old('skill_tags') }}" placeholder="e.g., JavaScript, React, Node.js (comma separated)">
                @error('skill_tags')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Content Type</label>
                <select class="adomx-form-input @error('content_type') adomx-input-error @enderror" name="content_type">
                    <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>Video</option>
                    <option value="pdf" {{ old('content_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="scorm" {{ old('content_type') == 'scorm' ? 'selected' : '' }}>SCORM</option>
                    <option value="ar_vr" {{ old('content_type') == 'ar_vr' ? 'selected' : '' }}>AR/VR</option>
                    <option value="interactive" {{ old('content_type') == 'interactive' ? 'selected' : '' }}>Interactive</option>
                </select>
                @error('content_type')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Course Thumbnail</label>
                <input type="file" class="adomx-form-input @error('thumbnail') adomx-input-error @enderror" name="thumbnail" accept="image/*">
                <small class="adomx-form-hint">Recommended size: 800x450px</small>
                @error('thumbnail')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Course
                </button>
                <a href="{{ route('teacher.courses.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

