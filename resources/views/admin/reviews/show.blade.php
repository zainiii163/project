@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Review Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.reviews.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="margin-bottom: 30px;">
            <h3>Review Information</h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>User:</strong> {{ $review->user->name ?? 'N/A' }}
            </div>
            <div>
                <strong>Course:</strong> {{ $review->course->title ?? 'N/A' }}
            </div>
            <div>
                <strong>Rating:</strong>
                <div style="display: flex; align-items: center; gap: 5px; margin-top: 5px;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 20px;"></i>
                    @endfor
                    <span>({{ $review->rating }}/5)</span>
                </div>
            </div>
            <div>
                <strong>Status:</strong>
                <span class="adomx-status-badge adomx-status-{{ $review->status ?? 'pending' }}">
                    {{ ucfirst($review->status ?? 'pending') }}
                </span>
            </div>
            <div>
                <strong>Created:</strong> {{ $review->created_at->format('M d, Y H:i') }}
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <strong>Comment:</strong>
            <div style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px;">
                {{ $review->comment }}
            </div>
        </div>

        <div class="adomx-form-actions">
            @if(($review->status ?? 'pending') != 'approved')
                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-check"></i>
                        Approve Review
                    </button>
                </form>
            @endif
            @if(($review->status ?? 'pending') != 'rejected')
                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="adomx-btn" style="background: var(--warning-color);">
                        <i class="fas fa-times"></i>
                        Reject Review
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="adomx-btn" style="background: var(--danger-color);">
                    <i class="fas fa-trash"></i>
                    Delete Review
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

