@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Blog Post</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.blog.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Blog Post Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Title <span class="adomx-required">*</span></label>
                <input type="text" 
                       class="adomx-form-input @error('title') adomx-input-error @enderror" 
                       name="title" 
                       value="{{ old('title', $post->title) }}" 
                       required>
                @error('title')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Slug</label>
                <input type="text" 
                       class="adomx-form-input @error('slug') adomx-input-error @enderror" 
                       name="slug" 
                       value="{{ old('slug', $post->slug) }}">
                @error('slug')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Excerpt</label>
                <textarea class="adomx-form-input @error('excerpt') adomx-input-error @enderror" 
                          name="excerpt" 
                          rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Content <span class="adomx-required">*</span></label>
                <textarea class="adomx-form-input @error('content') adomx-input-error @enderror" 
                          name="content" 
                          rows="10" 
                          required>{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Category</label>
                    <select class="adomx-form-input @error('category_id') adomx-input-error @enderror" name="category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Status <span class="adomx-required">*</span></label>
                    <select class="adomx-form-input @error('status') adomx-input-error @enderror" name="status" required>
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Featured Image</label>
                @if($post->featured_image)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured Image" style="max-width: 200px; border-radius: 8px; border: 1px solid var(--border-color);">
                    </div>
                @endif
                <input type="file" 
                       class="adomx-form-input @error('featured_image') adomx-input-error @enderror" 
                       name="featured_image" 
                       accept="image/*">
                <small class="adomx-form-hint">Leave empty to keep current image</small>
                @error('featured_image')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Tags</label>
                <select class="adomx-form-input" name="tags[]" multiple style="min-height: 100px;">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ $post->tags->contains($tag->id) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <small class="adomx-form-hint">Hold Ctrl/Cmd to select multiple tags</small>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Meta Title</label>
                <input type="text" 
                       class="adomx-form-input @error('meta_title') adomx-input-error @enderror" 
                       name="meta_title" 
                       value="{{ old('meta_title', $post->meta_title) }}"
                       maxlength="255">
                @error('meta_title')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Meta Description</label>
                <textarea class="adomx-form-input @error('meta_description') adomx-input-error @enderror" 
                          name="meta_description" 
                          rows="3">{{ old('meta_description', $post->meta_description) }}</textarea>
                @error('meta_description')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Post
                </button>
                <a href="{{ route('admin.blog.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

