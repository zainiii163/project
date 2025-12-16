@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create New User</h2>
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
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Full Name <span class="adomx-required">*</span></label>
                    <input type="text" 
                           class="adomx-form-input @error('name') adomx-input-error @enderror" 
                           name="name" 
                           value="{{ old('name') }}" 
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
                           value="{{ old('email') }}" 
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
                           value="{{ old('username') }}">
                    @error('username')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Role <span class="adomx-required">*</span></label>
                    <select class="adomx-form-input @error('role') adomx-input-error @enderror" name="role" required>
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Password <span class="adomx-required">*</span></label>
                    <input type="password" 
                           class="adomx-form-input @error('password') adomx-input-error @enderror" 
                           name="password" 
                           required>
                    @error('password')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Confirm Password <span class="adomx-required">*</span></label>
                    <input type="password" 
                           class="adomx-form-input" 
                           name="password_confirmation" 
                           required>
                </div>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
