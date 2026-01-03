@extends('layouts.main')

@section('content')
<main id="main-content" role="main" aria-label="Resource Library">
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Resource Library</h1>
            <p class="text-muted">Browse and download shared learning materials</p>
        </div>
        @auth
        @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
        <div class="col-md-4 text-right">
            <a href="{{ route('resources.create') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Resource
            </a>
        </div>
        @endif
        @endauth
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" action="{{ route('resources.index') }}" class="form-inline">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search resources..." value="{{ request('search') }}">
                <select name="category" class="form-control mr-2">
                    <option value="">All Categories</option>
                    <option value="document" {{ request('category') == 'document' ? 'selected' : '' }}>Documents</option>
                    <option value="video" {{ request('category') == 'video' ? 'selected' : '' }}>Videos</option>
                    <option value="audio" {{ request('category') == 'audio' ? 'selected' : '' }}>Audio</option>
                    <option value="image" {{ request('category') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($resources as $resource)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $resource->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($resource->description, 100) }}</p>
                    <div class="mb-2">
                        <span class="badge badge-secondary">{{ $resource->category }}</span>
                        <span class="badge badge-info">{{ number_format($resource->file_size / 1024 / 1024, 2) }} MB</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-download"></i> {{ $resource->download_count }} downloads
                        </small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('resources.download', $resource) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Download
                        </a>
                        @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->id == $resource->uploaded_by)
                        <form action="{{ route('resources.destroy', $resource) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <div class="alert alert-info">No resources found.</div>
        </div>
        @endforelse
    </div>

    {{ $resources->links() }}
</div>
</main>
@endsection

