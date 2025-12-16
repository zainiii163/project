@extends('layouts.admin')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Reviews & Ratings Management</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Reviews</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.reviews.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search by user..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="course_id" class="adomx-search-input" style="max-width: 200px;">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
            <select name="rating" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Ratings</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
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
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td><strong>{{ $review->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ $review->course->title ?? 'N/A' }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span>({{ $review->rating }})</span>
                            </div>
                        </td>
                        <td>{{ Str::limit($review->comment, 50) }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $review->status ?? 'pending' }}">
                                {{ ucfirst($review->status ?? 'pending') }}
                            </span>
                        </td>
                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.reviews.show', $review) }}" class="adomx-action-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(($review->status ?? 'pending') != 'approved')
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Approve" style="color: var(--success-color);">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                @if(($review->status ?? 'pending') != 'rejected')
                                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Reject" style="color: var(--warning-color);">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
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
                        <td colspan="7" class="adomx-table-empty">No reviews found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $reviews->links() }}
    </div>
</div>
@endsection

