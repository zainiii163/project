@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Send Notification</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.notifications.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('admin.notifications.store') }}" method="POST">
            @csrf

            <div class="adomx-form-group">
                <label for="user_id" class="adomx-label">User <span class="text-danger">*</span></label>
                <select id="user_id" name="user_id" class="adomx-input @error('user_id') is-invalid @enderror" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="type" class="adomx-label">Type <span class="text-danger">*</span></label>
                    <select id="type" name="type" class="adomx-input @error('type') is-invalid @enderror" required>
                        <option value="course" {{ old('type') == 'course' ? 'selected' : '' }}>Course</option>
                        <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                        <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                        <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>System</option>
                    </select>
                    @error('type')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group">
                <label for="title" class="adomx-label">Title <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" class="adomx-input @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="message" class="adomx-label">Message <span class="text-danger">*</span></label>
                <textarea id="message" name="message" class="adomx-input @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
                @error('message')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Send Notification
                </button>
                <a href="{{ route('admin.notifications.index') }}" class="adomx-btn adomx-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

