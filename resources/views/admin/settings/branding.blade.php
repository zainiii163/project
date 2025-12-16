@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Branding Settings</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Branding Configuration</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.settings.branding.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Site Name</label>
                <input type="text" 
                       class="adomx-form-input @error('site_name') adomx-input-error @enderror" 
                       name="site_name" 
                       value="{{ old('site_name', 'SmartLearn LMS') }}">
                @error('site_name')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Logo</label>
                    <input type="file" 
                           class="adomx-form-input @error('logo') adomx-input-error @enderror" 
                           name="logo" 
                           accept="image/*">
                    <small class="adomx-form-hint">Recommended size: 200x50px</small>
                    @error('logo')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Favicon</label>
                    <input type="file" 
                           class="adomx-form-input @error('favicon') adomx-input-error @enderror" 
                           name="favicon" 
                           accept="image/*">
                    <small class="adomx-form-hint">Recommended size: 32x32px</small>
                    @error('favicon')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Primary Color</label>
                    <input type="color" 
                           class="adomx-form-input @error('primary_color') adomx-input-error @enderror" 
                           name="primary_color" 
                           value="{{ old('primary_color', '#4f46e5') }}">
                    @error('primary_color')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Secondary Color</label>
                    <input type="color" 
                           class="adomx-form-input @error('secondary_color') adomx-input-error @enderror" 
                           name="secondary_color" 
                           value="{{ old('secondary_color', '#10b981') }}">
                    @error('secondary_color')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Branding
                </button>
                <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

