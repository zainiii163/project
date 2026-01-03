@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Post
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Edit Blog Post</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('blog.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                    @error('title')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" class="form-control" rows="3" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea>
                    <small class="form-text text-muted">A brief summary of the post (max 500 characters). Used for SEO and previews.</small>
                    @error('excerpt')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Content <span class="text-danger">*</span></label>
                    <textarea name="content" id="content" class="form-control" rows="15" required>{{ old('content', $post->content) }}</textarea>
                    <small class="form-text text-muted">You can use HTML formatting in the content.</small>
                    @error('content')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Current Featured Image</label>
                    @if($post->featured_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="max-width: 300px; height: auto;">
                    </div>
                    @else
                    <p class="text-muted">No featured image</p>
                    @endif
                    <label for="featured_image">Upload New Featured Image</label>
                    <input type="file" name="featured_image" id="featured_image" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Leave empty to keep current image. Recommended size: 1200x630px.</small>
                    @error('featured_image')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_ids">Categories</label>
                            <select name="category_ids[]" id="category_ids" class="form-control" multiple>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', $post->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple categories.</small>
                            @error('category_ids')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tag_ids">Tags</label>
                            <select name="tag_ids[]" id="tag_ids" class="form-control" multiple>
                                @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_ids', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple tags.</small>
                            @error('tag_ids')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Post
                    </button>
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-secondary">Cancel</a>
                    <form action="{{ route('blog.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

