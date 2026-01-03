@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit SEO Meta Tags</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.seo.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">SEO Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.seo.update', $seoMeta) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group">
                <label class="adomx-label">Content</label>
                <div class="adomx-input" style="background: #f8f9fa; padding: 12px;">
                    @if($seoMeta->model)
                        <strong>{{ class_basename($seoMeta->model_type) }}:</strong> {{ $seoMeta->model->title ?? $seoMeta->model->name ?? 'N/A' }}
                    @else
                        N/A
                    @endif
                </div>
            </div>

            <div class="adomx-form-group">
                <label for="meta_title" class="adomx-label">Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" class="adomx-input" value="{{ old('meta_title', $seoMeta->meta_title) }}" maxlength="255">
                <small class="form-text text-muted">Recommended: 50-60 characters</small>
                @error('meta_title')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="meta_description" class="adomx-label">Meta Description</label>
                <textarea id="meta_description" name="meta_description" class="adomx-input" rows="3" maxlength="500">{{ old('meta_description', $seoMeta->meta_description) }}</textarea>
                <small class="form-text text-muted">Recommended: 150-160 characters</small>
                @error('meta_description')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="meta_keywords" class="adomx-label">Meta Keywords</label>
                <input type="text" id="meta_keywords" name="meta_keywords" class="adomx-input" value="{{ old('meta_keywords', $seoMeta->meta_keywords) }}" placeholder="keyword1, keyword2, keyword3">
                <small class="form-text text-muted">Comma-separated keywords</small>
                @error('meta_keywords')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <h4 style="margin-top: 30px; margin-bottom: 15px;">Open Graph Tags</h4>

            <div class="adomx-form-group">
                <label for="og_title" class="adomx-label">OG Title</label>
                <input type="text" id="og_title" name="og_title" class="adomx-input" value="{{ old('og_title', $seoMeta->og_title) }}" maxlength="255">
                @error('og_title')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="og_description" class="adomx-label">OG Description</label>
                <textarea id="og_description" name="og_description" class="adomx-input" rows="3" maxlength="500">{{ old('og_description', $seoMeta->og_description) }}</textarea>
                @error('og_description')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="og_image" class="adomx-label">OG Image URL</label>
                <input type="url" id="og_image" name="og_image" class="adomx-input" value="{{ old('og_image', $seoMeta->og_image) }}" placeholder="https://example.com/image.jpg">
                @error('og_image')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <h4 style="margin-top: 30px; margin-bottom: 15px;">Twitter Card Tags</h4>

            <div class="adomx-form-group">
                <label for="twitter_title" class="adomx-label">Twitter Title</label>
                <input type="text" id="twitter_title" name="twitter_title" class="adomx-input" value="{{ old('twitter_title', $seoMeta->twitter_title) }}" maxlength="255">
                @error('twitter_title')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="twitter_description" class="adomx-label">Twitter Description</label>
                <textarea id="twitter_description" name="twitter_description" class="adomx-input" rows="3" maxlength="500">{{ old('twitter_description', $seoMeta->twitter_description) }}</textarea>
                @error('twitter_description')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="twitter_image" class="adomx-label">Twitter Image URL</label>
                <input type="url" id="twitter_image" name="twitter_image" class="adomx-input" value="{{ old('twitter_image', $seoMeta->twitter_image) }}" placeholder="https://example.com/image.jpg">
                @error('twitter_image')
                <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i> Update SEO Meta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

