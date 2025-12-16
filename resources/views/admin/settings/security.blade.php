@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Security Settings</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Security Configuration</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.settings.security.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Password Minimum Length <span class="adomx-required">*</span></label>
                <input type="number" 
                       min="6" 
                       max="50" 
                       class="adomx-form-input @error('password_min_length') adomx-input-error @enderror" 
                       name="password_min_length" 
                       value="{{ old('password_min_length', 8) }}" 
                       required>
                @error('password_min_length')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Password Requirements</label>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px;">
                    <label>
                        <input type="checkbox" name="password_require_uppercase" value="1" {{ old('password_require_uppercase', true) ? 'checked' : '' }}>
                        Require Uppercase
                    </label>
                    <label>
                        <input type="checkbox" name="password_require_lowercase" value="1" {{ old('password_require_lowercase', true) ? 'checked' : '' }}>
                        Require Lowercase
                    </label>
                    <label>
                        <input type="checkbox" name="password_require_numbers" value="1" {{ old('password_require_numbers', true) ? 'checked' : '' }}>
                        Require Numbers
                    </label>
                    <label>
                        <input type="checkbox" name="password_require_symbols" value="1" {{ old('password_require_symbols', false) ? 'checked' : '' }}>
                        Require Symbols
                    </label>
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" name="two_factor_enabled" value="1" {{ old('two_factor_enabled', false) ? 'checked' : '' }}>
                    Enable Two-Factor Authentication
                </label>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Session Timeout (minutes)</label>
                <input type="number" 
                       min="5" 
                       class="adomx-form-input @error('session_timeout') adomx-input-error @enderror" 
                       name="session_timeout" 
                       value="{{ old('session_timeout', 120) }}">
                @error('session_timeout')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Max Login Attempts</label>
                <input type="number" 
                       min="3" 
                       class="adomx-form-input @error('max_login_attempts') adomx-input-error @enderror" 
                       name="max_login_attempts" 
                       value="{{ old('max_login_attempts', 5) }}">
                @error('max_login_attempts')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Security Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

