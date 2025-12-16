@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Course</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Courses
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Course Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="adomx-form-group">
                <label for="title" class="adomx-label">Title <span class="text-danger">*</span></label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       class="adomx-input @error('title') is-invalid @enderror" 
                       value="{{ old('title', $course->title) }}" 
                       required>
                @error('title')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="description" class="adomx-label">Description</label>
                <textarea id="description" 
                          name="description" 
                          class="adomx-input @error('description') is-invalid @enderror" 
                          rows="5">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="teacher_id" class="adomx-label">Teacher <span class="text-danger">*</span></label>
                    <select id="teacher_id" 
                            name="teacher_id" 
                            class="adomx-input @error('teacher_id') is-invalid @enderror" 
                            required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="category_id" class="adomx-label">Category</label>
                    <select id="category_id" 
                            name="category_id" 
                            class="adomx-input @error('category_id') is-invalid @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="price" class="adomx-label">Price <span class="text-danger">*</span></label>
                    <input type="number" 
                           id="price" 
                           name="price" 
                           class="adomx-input @error('price') is-invalid @enderror" 
                           value="{{ old('price', $course->price) }}" 
                           step="0.01" 
                           min="0" 
                           required>
                    @error('price')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="level" class="adomx-label">Level <span class="text-danger">*</span></label>
                    <select id="level" 
                            name="level" 
                            class="adomx-input @error('level') is-invalid @enderror" 
                            required>
                        <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('level')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="duration" class="adomx-label">Duration (minutes)</label>
                    <input type="number" 
                           id="duration" 
                           name="duration" 
                           class="adomx-input @error('duration') is-invalid @enderror" 
                           value="{{ old('duration', $course->duration) }}" 
                           min="0">
                    @error('duration')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="status" class="adomx-label">Status <span class="text-danger">*</span></label>
                    <select id="status" 
                            name="status" 
                            class="adomx-input @error('status') is-invalid @enderror" 
                            required>
                        <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="thumbnail" class="adomx-label">Thumbnail</label>
                    @if($course->thumbnail)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current thumbnail" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    @endif
                    <input type="file" 
                           id="thumbnail" 
                           name="thumbnail" 
                           class="adomx-input @error('thumbnail') is-invalid @enderror" 
                           accept="image/*">
                    @error('thumbnail')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group">
                <label for="objectives" class="adomx-label">Learning Objectives</label>
                <textarea id="objectives" 
                          name="objectives" 
                          class="adomx-input @error('objectives') is-invalid @enderror" 
                          rows="3">{{ old('objectives', $course->objectives) }}</textarea>
                @error('objectives')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="requirements" class="adomx-label">Requirements</label>
                <textarea id="requirements" 
                          name="requirements" 
                          class="adomx-input @error('requirements') is-invalid @enderror" 
                          rows="3">{{ old('requirements', $course->requirements) }}</textarea>
                @error('requirements')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Course
                </button>
                <a href="{{ route('admin.courses.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

