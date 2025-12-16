@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>SEO Settings</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">SEO Configuration</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.settings.seo.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Meta Title</label>
                <input type="text" 
                       class="adomx-form-input @error('meta_title') adomx-input-error @enderror" 
                       name="meta_title" 
                       value="{{ old('meta_title', 'SmartLearn LMS - Online Learning Platform') }}"
                       maxlength="255">
                <small class="adomx-form-hint">Recommended: 50-60 characters</small>
                @error('meta_title')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Meta Description</label>
                <textarea class="adomx-form-input @error('meta_description') adomx-input-error @enderror" 
                          name="meta_description" 
                          rows="3"
                          maxlength="500">{{ old('meta_description', 'Learn new skills with our comprehensive online courses. Expert instructors, flexible learning, and certificates upon completion.') }}</textarea>
                <small class="adomx-form-hint">Recommended: 150-160 characters</small>
                @error('meta_description')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Meta Keywords</label>
                <input type="text" 
                       class="adomx-form-input @error('meta_keywords') adomx-input-error @enderror" 
                       name="meta_keywords" 
                       value="{{ old('meta_keywords', 'online learning, courses, education, e-learning, LMS') }}"
                       placeholder="Comma-separated keywords"
                       maxlength="500">
                @error('meta_keywords')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Open Graph Image</label>
                <input type="file" 
                       class="adomx-form-input @error('og_image') adomx-input-error @enderror" 
                       name="og_image" 
                       accept="image/*">
                <small class="adomx-form-hint">Recommended size: 1200x630px</small>
                @error('og_image')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update SEO Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

