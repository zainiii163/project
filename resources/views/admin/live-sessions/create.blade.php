@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Live Session</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.live-sessions.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Session Details</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.live-sessions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="course_id">Course <span class="text-danger">*</span></label>
                <select name="course_id" id="course_id" class="form-control" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                    @endforeach
                </select>
                @error('course_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="teacher_id">Teacher <span class="text-danger">*</span></label>
                <select name="teacher_id" id="teacher_id" class="form-control" required>
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                    @endforeach
                </select>
                @error('teacher_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="scheduled_at">Scheduled At <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}" required>
                        @error('scheduled_at')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="duration_minutes">Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="{{ old('duration_minutes', 60) }}" min="1" max="480" required>
                        @error('duration_minutes')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="platform">Platform <span class="text-danger">*</span></label>
                <select name="platform" id="platform" class="form-control" required>
                    <option value="">Select Platform</option>
                    <option value="zoom" {{ old('platform') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                    <option value="google_meet" {{ old('platform') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                    <option value="teams" {{ old('platform') == 'teams' ? 'selected' : '' }}>Microsoft Teams</option>
                    <option value="other" {{ old('platform') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('platform')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="meeting_url">Meeting URL <span class="text-danger">*</span></label>
                <input type="url" name="meeting_url" id="meeting_url" class="form-control" value="{{ old('meeting_url') }}" required>
                <small class="form-text text-muted">The meeting link will be generated automatically if using Zoom/Google Meet API, or enter manually.</small>
                @error('meeting_url')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="meeting_id">Meeting ID</label>
                        <input type="text" name="meeting_id" id="meeting_id" class="form-control" value="{{ old('meeting_id') }}">
                        @error('meeting_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="meeting_password">Meeting Password</label>
                        <input type="text" name="meeting_password" id="meeting_password" class="form-control" value="{{ old('meeting_password') }}">
                        @error('meeting_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i> Create Live Session
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

