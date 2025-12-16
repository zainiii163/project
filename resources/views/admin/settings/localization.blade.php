@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Localization Settings</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Localization Configuration</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.settings.localization.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Default Language <span class="adomx-required">*</span></label>
                <select class="adomx-form-input @error('default_language') adomx-input-error @enderror" name="default_language" required>
                    @foreach($languages as $code => $name)
                        <option value="{{ $code }}" {{ old('default_language', 'en') == $code ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('default_language')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Supported Languages <span class="adomx-required">*</span></label>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px;">
                    @foreach($languages as $code => $name)
                        <label>
                            <input type="checkbox" 
                                   name="supported_languages[]" 
                                   value="{{ $code }}" 
                                   {{ old('supported_languages', ['en']) && in_array($code, old('supported_languages', ['en'])) ? 'checked' : '' }}>
                            {{ $name }}
                        </label>
                    @endforeach
                </div>
                @error('supported_languages')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Timezone <span class="adomx-required">*</span></label>
                <select class="adomx-form-input @error('timezone') adomx-input-error @enderror" name="timezone" required>
                    <option value="UTC" {{ old('timezone', 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                    <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                    <option value="America/Chicago" {{ old('timezone') == 'America/Chicago' ? 'selected' : '' }}>America/Chicago (CST)</option>
                    <option value="America/Denver" {{ old('timezone') == 'America/Denver' ? 'selected' : '' }}>America/Denver (MST)</option>
                    <option value="America/Los_Angeles" {{ old('timezone') == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                    <option value="Europe/London" {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                    <option value="Europe/Paris" {{ old('timezone') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (CET)</option>
                    <option value="Asia/Dubai" {{ old('timezone') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                    <option value="Asia/Tokyo" {{ old('timezone') == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (JST)</option>
                </select>
                @error('timezone')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Date Format <span class="adomx-required">*</span></label>
                <select class="adomx-form-input @error('date_format') adomx-input-error @enderror" name="date_format" required>
                    <option value="Y-m-d" {{ old('date_format', 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                    <option value="m/d/Y" {{ old('date_format') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                    <option value="d/m/Y" {{ old('date_format') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                    <option value="d M Y" {{ old('date_format') == 'd M Y' ? 'selected' : '' }}>DD MMM YYYY</option>
                </select>
                @error('date_format')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Localization
                </button>
                <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

