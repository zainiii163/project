@extends('layouts.main')

@section('content')
<div class="container py-5">
    <h1>Upload Resource</h1>

    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>File <span class="text-danger">*</span></label>
            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Max file size: 100MB</small>
        </div>

        <div class="form-group">
            <label>Category <span class="text-danger">*</span></label>
            <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                <option value="document">Document</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
                <option value="image">Image</option>
                <option value="other">Other</option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Tags (comma-separated)</label>
            <input type="text" name="tags" class="form-control" placeholder="e.g., tutorial, guide, reference">
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="is_public" value="1" class="custom-control-input" id="is_public" {{ old('is_public') ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_public">Make this resource public</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Upload Resource</button>
    </form>
</div>
@endsection

