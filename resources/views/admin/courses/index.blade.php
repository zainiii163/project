@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Course Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.courses.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Course
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Courses</h3>
        <i class="fas fa-cog"></i>
    </div>
    
    <!-- Filters -->
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.courses.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="text" 
                   name="search" 
                   class="adomx-search-input" 
                   placeholder="Search courses..." 
                   value="{{ request('search') }}"
                   style="flex: 1; min-width: 200px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <select name="category" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
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
                    <th>
                        <input type="checkbox" class="adomx-checkbox">
                    </th>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Teacher</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td>
                            <input type="checkbox" class="adomx-checkbox">
                        </td>
                        <td>
                            <div class="adomx-table-image">
                                <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://via.placeholder.com/80x60?text=Course' }}" 
                                     alt="{{ $course->title }}" style="width: 80px; height: 60px; object-fit: cover;">
                            </div>
                        </td>
                        <td>
                            <strong>{{ $course->title }}</strong>
                        </td>
                        <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                        <td>{{ $course->category->name ?? 'Uncategorized' }}</td>
                        <td>${{ number_format($course->price, 2) }}</td>
                        <td>{{ $course->students->count() ?? 0 }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $course->status }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('courses.show', $course->slug) }}" class="adomx-action-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($course->status == 'draft')
                                    <form action="{{ route('admin.courses.publish', $course) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Publish">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.courses.unpublish', $course) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Unpublish">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
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
                        <td colspan="10" class="adomx-table-empty">No courses found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $courses->links() }}
    </div>
</div>

<style>
.adomx-status-published {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
}

.adomx-status-draft {
    background: rgba(107, 114, 128, 0.1);
    color: var(--text-secondary);
}
</style>
@endsection

