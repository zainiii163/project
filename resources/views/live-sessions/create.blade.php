@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create Live Session</h1>
        <a href="{{ route('teacher.live-sessions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Session Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.live-sessions.store') }}" method="POST">
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Live Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

