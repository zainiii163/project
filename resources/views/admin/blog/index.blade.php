@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Blog Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.blog.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Post
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Blog Posts</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.blog.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search posts..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <select name="category_id" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td><strong>{{ $post->title }}</strong></td>
                        <td>{{ $post->author->name ?? 'N/A' }}</td>
                        <td>{{ $post->category->name ?? 'Uncategorized' }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $post->status }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td>{{ $post->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('blog.show', $post->slug) }}" class="adomx-action-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.blog.edit', $post) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="adomx-action-btn" title="Delete" style="color: var(--danger-color);">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
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

