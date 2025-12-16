@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Announcement Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.announcements.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Announcement
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Announcements</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.announcements.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search announcements..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="scope" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Scopes</option>
                <option value="all" {{ request('scope') == 'all' ? 'selected' : '' }}>All</option>
                <option value="students" {{ request('scope') == 'students' ? 'selected' : '' }}>Students</option>
                <option value="teachers" {{ request('scope') == 'teachers' ? 'selected' : '' }}>Teachers</option>
                <option value="admins" {{ request('scope') == 'admins' ? 'selected' : '' }}>Admins</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Scope</th>
                    <th>Recipients</th>
                    <th>Priority</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                    <tr>
                        <td><strong>{{ $announcement->title }}</strong></td>
                        <td>{{ ucfirst($announcement->scope) }}</td>
                        <td>{{ $announcement->users_count ?? 0 }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $announcement->priority ?? 'medium' }}">
                                {{ ucfirst($announcement->priority ?? 'medium') }}
                            </span>
                        </td>
                        <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
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
                        <td colspan="6" class="adomx-table-empty">No announcements found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $announcements->links() }}
    </div>
</div>
@endsection

