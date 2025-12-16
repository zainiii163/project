@extends('layouts.admin')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Reviews</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All My Reviews</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td><strong>{{ $review->course->title ?? 'N/A' }}</strong></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span>({{ $review->rating }})</span>
                            </div>
                        </td>
                        <td>{{ Str::limit($review->comment, 50) }}</td>
                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('courses.show', $review->course->slug ?? '#') }}" class="adomx-action-btn" title="View Course">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
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
                        <td colspan="5" class="adomx-table-empty">No reviews yet</td>
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

