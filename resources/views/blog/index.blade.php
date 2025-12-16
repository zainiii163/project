@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Blog Posts</h2>
    </div>
    @can('create', App\Models\BlogPost::class)
    <div class="adomx-page-actions">
        <a href="{{ route('blog.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Post
        </a>
    </div>
    @endcan
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">All Published Posts</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('blog.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search posts..." value="{{ request('search') }}" style="flex: 1; min-width: 200px;">
            <select name="category" class="adomx-search-input" style="max-width: 200px;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <select name="tag" class="adomx-search-input" style="max-width: 200px;">
                <option value="">All Tags</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                        {{ $tag->name }}
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
                        @else
                            <div style="width: 100%; height: 200px; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; border-radius: 8px 8px 0 0;">
                                <i class="fas fa-newspaper" style="font-size: 48px; color: var(--text-secondary);"></i>
                            </div>
                        @endif
                        <div class="adomx-card-body">
                            <div style="display: flex; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
                                @foreach($post->categories as $category)
                                    <span style="background: var(--primary-color); color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px;">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                            <h4 style="margin-bottom: 10px; font-size: 18px;">{{ $post->title }}</h4>
                            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-top: 15px; border-top: 1px solid var(--border-color);">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">
                                        {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; font-weight: 500;">{{ $post->author->name ?? 'Admin' }}</div>
                                        <div style="font-size: 11px; color: var(--text-secondary);">
                                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                                @if($post->tags->count() > 0)
                                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                        @foreach($post->tags->take(2) as $tag)
                                            <span style="background: var(--bg-secondary); padding: 3px 8px; border-radius: 4px; font-size: 11px; color: var(--text-secondary);">
                                                #{{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('blog.show', $post->slug) }}" class="adomx-btn adomx-btn-primary" style="width: 100%;">
                                Read More
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="padding: 20px 0; margin-top: 20px; border-top: 1px solid var(--border-color);">
                {{ $posts->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-newspaper" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                <h3 style="margin-bottom: 10px;">No Posts Found</h3>
                <p style="color: var(--text-secondary);">Try adjusting your search or filter criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection

