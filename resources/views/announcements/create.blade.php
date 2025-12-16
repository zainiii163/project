@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Announcement</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('announcements.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Announcement Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('announcements.store') }}" method="POST">
            @csrf

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Title <span class="adomx-required">*</span></label>
                <input type="text" 
                       class="adomx-form-input @error('title') adomx-input-error @enderror" 
                       name="title" 
                       value="{{ old('title') }}" 
                       required
                       placeholder="Enter announcement title">
                @error('title')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Content <span class="adomx-required">*</span></label>
                <textarea class="adomx-form-input @error('content') adomx-input-error @enderror" 
                          name="content" 
                          rows="6" 
                          required
                          placeholder="Enter announcement content">{{ old('content') }}</textarea>
                @error('content')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Scope <span class="adomx-required">*</span></label>
                <select id="scope" 
                        class="adomx-form-input @error('scope') adomx-input-error @enderror" 
                        name="scope" 
                        required
                        onchange="toggleScopeFields()">
                    <option value="all" {{ old('scope') == 'all' ? 'selected' : '' }}>All Users</option>
                    <option value="course" {{ old('scope') == 'course' ? 'selected' : '' }}>Specific Course</option>
                    <option value="user" {{ old('scope') == 'user' ? 'selected' : '' }}>Specific User</option>
                </select>
                @error('scope')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div id="courseField" class="adomx-form-group" style="margin-bottom: 20px; display: {{ old('scope') == 'course' ? 'block' : 'none' }};">
                <label class="adomx-form-label">Course <span class="adomx-required">*</span></label>
                <select class="adomx-form-input @error('course_id') adomx-input-error @enderror" 
                        name="course_id">
                    <option value="">Select a course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div id="userField" class="adomx-form-group" style="margin-bottom: 20px; display: {{ old('scope') == 'user' ? 'block' : 'none' }};">
                <label class="adomx-form-label">User <span class="adomx-required">*</span></label>
                <select class="adomx-form-input @error('user_id') adomx-input-error @enderror" 
                        name="user_id">
                    <option value="">Select a user</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Create & Send Announcement
                </button>
                <a href="{{ route('announcements.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleScopeFields() {
    const scope = document.getElementById('scope').value;
    const courseField = document.getElementById('courseField');
    const userField = document.getElementById('userField');
    
    if (scope === 'course') {
        courseField.style.display = 'block';
        userField.style.display = 'none';
        courseField.querySelector('select').required = true;
        userField.querySelector('select').required = false;
    } else if (scope === 'user') {
        courseField.style.display = 'none';
        userField.style.display = 'block';
        courseField.querySelector('select').required = false;
        userField.querySelector('select').required = true;
    } else {
        courseField.style.display = 'none';
        userField.style.display = 'none';
        courseField.querySelector('select').required = false;
        userField.querySelector('select').required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleScopeFields();
});
</script>
@endsection

