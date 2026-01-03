@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Live Session</h2>
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
        <form action="{{ route('admin.live-sessions.update', $liveSession) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="course_id">Course <span class="text-danger">*</span></label>
                <select name="course_id" id="course_id" class="form-control" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $liveSession->course_id) == $course->id ? 'selected' : '' }}>
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
                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $liveSession->teacher_id) == $teacher->id ? 'selected' : '' }}>
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
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $liveSession->title) }}" required>
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $liveSession->description) }}</textarea>
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="scheduled_at">Scheduled At <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control" value="{{ old('scheduled_at', $liveSession->scheduled_at->format('Y-m-d\TH:i')) }}" required>
                        @error('scheduled_at')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="duration_minutes">Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="{{ old('duration_minutes', $liveSession->duration_minutes) }}" min="1" max="480" required>
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
                    <option value="zoom" {{ old('platform', $liveSession->platform) == 'zoom' ? 'selected' : '' }}>Zoom</option>
                    <option value="google_meet" {{ old('platform', $liveSession->platform) == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                    <option value="teams" {{ old('platform', $liveSession->platform) == 'teams' ? 'selected' : '' }}>Microsoft Teams</option>
                    <option value="other" {{ old('platform', $liveSession->platform) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('platform')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control" required>
                    <option value="scheduled" {{ old('status', $liveSession->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="live" {{ old('status', $liveSession->status) == 'live' ? 'selected' : '' }}>Live</option>
                    <option value="completed" {{ old('status', $liveSession->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $liveSession->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="meeting_url">Meeting URL <span class="text-danger">*</span></label>
                <input type="url" name="meeting_url" id="meeting_url" class="form-control" value="{{ old('meeting_url', $liveSession->meeting_url) }}" required>
                @error('meeting_url')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="meeting_id">Meeting ID</label>
                        <input type="text" name="meeting_id" id="meeting_id" class="form-control" value="{{ old('meeting_id', $liveSession->meeting_id) }}">
                        @error('meeting_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="meeting_password">Meeting Password</label>
                        <input type="text" name="meeting_password" id="meeting_password" class="form-control" value="{{ old('meeting_password', $liveSession->meeting_password) }}">
                        @error('meeting_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i> Update Live Session
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

