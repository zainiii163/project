@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit User</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.users.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary);">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">User Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Full Name <span class="adomx-required">*</span></label>
                    <input type="text" 
                           class="adomx-form-input @error('name') adomx-input-error @enderror" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                    @error('name')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Email <span class="adomx-required">*</span></label>
                    <input type="email" 
                           class="adomx-form-input @error('email') adomx-input-error @enderror" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Username</label>
                    <input type="text" 
                           class="adomx-form-input @error('username') adomx-input-error @enderror" 
                           name="username" 
                           value="{{ old('username', $user->username) }}">
                    @error('username')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Role <span class="adomx-required">*</span></label>
                    <select class="adomx-form-input @error('role') adomx-input-error @enderror" name="role" required>
                        <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        @if(auth()->user()->isSuperAdmin())
                        <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        @endif
                    </select>
                    @error('role')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">New Password</label>
                    <input type="password" 
                           class="adomx-form-input @error('password') adomx-input-error @enderror" 
                           name="password">
                    <small class="adomx-form-hint">Leave blank to keep current password</small>
                    @error('password')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Confirm New Password</label>
                    <input type="password" 
                           class="adomx-form-input" 
                           name="password_confirmation">
                </div>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
