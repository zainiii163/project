@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Blog Posts</h2>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">All Published Posts</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('student.blog.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search posts..." value="{{ request('search') }}" style="flex: 1; min-width: 200px;">
            <select name="category" class="adomx-search-input" style="max-width: 200px;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Search</button>
        </form>
    </div>

    <div class="adomx-card-body">
        @if($posts->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
                @foreach($posts as $post)
                    <div class="adomx-card" style="cursor: pointer;" onclick="window.location='{{ route('blog.show', $post->slug) }}'">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        @endif
                        <div style="padding: 20px;">
                            <h3 style="margin-bottom: 10px; color: var(--text-primary);">{{ $post->title }}</h3>
                            <p style="color: var(--text-secondary); margin-bottom: 15px;">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 150) }}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: var(--text-secondary);">
                                <span><i class="fas fa-user"></i> {{ $post->author->name }}</span>
                                <span><i class="fas fa-calendar"></i> {{ $post->published_at->format('M d, Y') }}</span>
                            </div>
                            @if($post->categories->count() > 0)
                                <div style="margin-top: 10px;">
                                    @foreach($post->categories as $category)
                                        <span class="adomx-status-badge" style="margin-right: 5px;">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="adomx-table-empty">No blog posts found</div>
        @endif
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $posts->links() }}
    </div>
</div>
@endsection

