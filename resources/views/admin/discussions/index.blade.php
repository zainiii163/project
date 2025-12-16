@extends('layouts.admin')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Discussions & Q&A Management</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Discussions</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.discussions.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search discussions..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="course_id" class="adomx-search-input" style="max-width: 200px;">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
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
                    <th>User</th>
                    <th>Course</th>
                    <th>Message</th>
                    <th>Replies</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($discussions as $discussion)
                    <tr>
                        <td><strong>{{ $discussion->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ $discussion->course->title ?? 'N/A' }}</td>
                        <td>{{ Str::limit($discussion->message, 100) }}</td>
                        <td>{{ $discussion->replies->count() ?? 0 }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $discussion->status ?? 'pending' }}">
                                {{ ucfirst($discussion->status ?? 'pending') }}
                            </span>
                        </td>
                        <td>{{ $discussion->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.discussions.show', $discussion) }}" class="adomx-action-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(($discussion->status ?? 'pending') != 'approved')
                                    <form action="{{ route('admin.discussions.approve', $discussion) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Approve" style="color: var(--success-color);">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                @if(($discussion->status ?? 'pending') != 'rejected')
                                    <form action="{{ route('admin.discussions.reject', $discussion) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Reject" style="color: var(--warning-color);">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.discussions.destroy', $discussion) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
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
                        <td colspan="7" class="adomx-table-empty">No discussions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $discussions->links() }}
    </div>
</div>
@endsection

