@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Blog Posts</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('blog.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i> Create Post
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All My Blog Posts</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('teacher.blog.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search posts..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Categories</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td><strong>{{ $post->title }}</strong></td>
                        <td>
                            @foreach($post->categories as $category)
                                <span class="adomx-status-badge">{{ $category->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $post->status }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td>{{ $post->views ?? 0 }}</td>
                        <td>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Not published' }}</td>
                        <td>
                            <a href="{{ route('blog.show', $post->slug) }}" class="adomx-btn adomx-btn-sm adomx-btn-primary" target="_blank">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('blog.edit', $post) }}" class="adomx-btn adomx-btn-sm adomx-btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No blog posts found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $posts->links() }}
    </div>
</div>
@endsection

