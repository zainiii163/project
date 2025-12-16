@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Category</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.categories.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group">
                <label for="name" class="adomx-label">Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="adomx-input @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="slug" class="adomx-label">Slug</label>
                <input type="text" id="slug" name="slug" class="adomx-input @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}">
                @error('slug')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="description" class="adomx-label">Description</label>
                <textarea id="description" name="description" class="adomx-input @error('description') is-invalid @enderror" rows="4">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="adomx-btn adomx-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

