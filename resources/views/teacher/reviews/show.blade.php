@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Review Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.reviews.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-8">
        <!-- Student Review -->
        <div class="adomx-card mb-4">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Student Review</h3>
            </div>
            <div class="adomx-card-body">
                <div class="mb-3">
                    <strong>Course:</strong> {{ $review->course->title }}
                </div>
                <div class="mb-3">
                    <strong>Student:</strong> {{ $review->user->name }}
                </div>
                <div class="mb-3">
                    <strong>Rating:</strong>
                    <div style="display: flex; align-items: center; gap: 5px; margin-top: 5px;">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star fa-2x {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                        <span class="ml-2">({{ $review->rating }}/5)</span>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Comment:</strong>
                    <p class="mt-2">{{ $review->comment ?? 'No comment provided.' }}</p>
                </div>
                <div>
                    <strong>Date:</strong> {{ $review->created_at->format('M d, Y H:i') }}
                </div>
                <div>
                    <strong>Status:</strong>
                    <span class="badge badge-{{ $review->status === 'approved' ? 'success' : ($review->status === 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($review->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Teacher Response -->
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Your Response</h3>
            </div>
            <div class="adomx-card-body">
                @if($review->teacher_response)
                <div class="mb-3 p-3 bg-light rounded">
                    <p>{{ $review->teacher_response }}</p>
                    <small class="text-muted">
                        Responded on {{ $review->teacher_response_at->format('M d, Y H:i') }}
                    </small>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editResponseModal">
                        <i class="fas fa-edit"></i> Edit Response
                    </button>
                    <form action="{{ route('teacher.reviews.delete-response', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete your response?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete Response
                        </button>
                    </form>
                </div>
                @else
                <form action="{{ route('teacher.reviews.respond', $review) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="teacher_response">Your Response <span class="text-danger">*</span></label>
                        <textarea name="teacher_response" id="teacher_response" class="form-control" rows="5" required placeholder="Thank the student for their feedback and address any concerns they may have raised..."></textarea>
                        <small class="form-text text-muted">Your response will be visible to the student and other course viewers.</small>
                    </div>
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-reply"></i> Post Response
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Course Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div class="mb-3">
                    <strong>Total Reviews:</strong> {{ $review->course->reviews()->where('status', 'approved')->count() }}
                </div>
                <div class="mb-3">
                    <strong>Average Rating:</strong>
                    @php
                        $avgRating = $review->course->reviews()->where('status', 'approved')->avg('rating') ?? 0;
                    @endphp
                    <div style="display: flex; align-items: center; gap: 5px; margin-top: 5px;">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                        <span class="ml-2">{{ number_format($avgRating, 1) }}/5</span>
                    </div>
                </div>
                <div>
                    <strong>Rating Distribution:</strong>
                    @for($i = 5; $i >= 1; $i--)
                    @php
                        $count = $review->course->reviews()->where('status', 'approved')->where('rating', $i)->count();
                        $total = $review->course->reviews()->where('status', 'approved')->count();
                        $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                    @endphp
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</span>
                            <span>{{ $count }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Response Modal -->
@if($review->teacher_response)
<div class="modal fade" id="editResponseModal" tabindex="-1" role="dialog" aria-labelledby="editResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('teacher.reviews.update-response', $review) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editResponseModalLabel">Edit Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_teacher_response">Your Response <span class="text-danger">*</span></label>
                        <textarea name="teacher_response" id="edit_teacher_response" class="form-control" rows="5" required>{{ $review->teacher_response }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Response</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

