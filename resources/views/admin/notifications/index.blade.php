@extends('layouts.admin')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Notification Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.notifications.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Send Notification
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Notifications</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.notifications.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search by user..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="type" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Types</option>
                <option value="course" {{ request('type') == 'course' ? 'selected' : '' }}>Course</option>
                <option value="assignment" {{ request('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
            </select>
            <select name="read" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All</option>
                <option value="read" {{ request('read') == 'read' ? 'selected' : '' }}>Read</option>
                <option value="unread" {{ request('read') == 'unread' ? 'selected' : '' }}>Unread</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Read</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                    <tr>
                        <td><strong>{{ $notification->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ ucfirst($notification->type) }}</td>
                        <td>{{ $notification->title ?? 'N/A' }}</td>
                        <td>{{ Str::limit($notification->message, 50) }}</td>
                        <td>
                            @if($notification->read_at)
                                <span class="adomx-status-badge adomx-status-published">Read</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">Unread</span>
                            @endif
                        </td>
                        <td>{{ $notification->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="adomx-action-btn" title="Delete" style="color: var(--danger-color);">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="adomx-table-empty">No notifications found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $notifications->links() }}
    </div>
</div>
@endsection

