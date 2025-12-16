@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Announcement</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.announcements.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('admin.announcements.store') }}" method="POST">
            @csrf

            <div class="adomx-form-group">
                <label for="title" class="adomx-label">Title <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" class="adomx-input @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="content" class="adomx-label">Content <span class="text-danger">*</span></label>
                <textarea id="content" name="content" class="adomx-input @error('content') is-invalid @enderror" rows="6" required>{{ old('content') }}</textarea>
                @error('content')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="scope" class="adomx-label">Scope <span class="text-danger">*</span></label>
                    <select id="scope" name="scope" class="adomx-input @error('scope') is-invalid @enderror" required>
                        <option value="all" {{ old('scope') == 'all' ? 'selected' : '' }}>All Users</option>
                        <option value="students" {{ old('scope') == 'students' ? 'selected' : '' }}>Students</option>
                        <option value="teachers" {{ old('scope') == 'teachers' ? 'selected' : '' }}>Teachers</option>
                        <option value="admins" {{ old('scope') == 'admins' ? 'selected' : '' }}>Admins</option>
                    </select>
                    @error('scope')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="priority" class="adomx-label">Priority</label>
                    <select id="priority" name="priority" class="adomx-input @error('priority') is-invalid @enderror">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group">
                <label for="recipient_ids" class="adomx-label">Specific Recipients (Optional - Leave empty to use scope)</label>
                <select id="recipient_ids" name="recipient_ids[]" class="adomx-input @error('recipient_ids') is-invalid @enderror" multiple>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, old('recipient_ids', [])) ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <small>Hold Ctrl/Cmd to select multiple users</small>
                @error('recipient_ids')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Announcement
                </button>
                <a href="{{ route('admin.announcements.index') }}" class="adomx-btn adomx-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

