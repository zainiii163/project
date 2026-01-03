@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Course Reviews</h2>
    </div>
</div>

<!-- Filters -->
<div class="adomx-card mb-4">
    <div class="adomx-card-body">
        <form method="GET" action="{{ route('teacher.reviews.index') }}" class="row g-3">
            <div class="col-md-4">
                <label>Course</label>
                <select name="course_id" class="form-control">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Rating</label>
                <select name="rating" class="form-control">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reviews List -->
<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Reviews</h3>
    </div>
    <div class="adomx-card-body">
        <div class="table-responsive">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Student</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Response</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td><strong>{{ $review->course->title }}</strong></td>
                        <td>{{ $review->user->name }}</td>
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
                            @if($review->teacher_response)
                            <span class="badge badge-success">Responded</span>
                            @else
                            <span class="badge badge-warning">No Response</span>
                            @endif
                        </td>
                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('teacher.reviews.show', $review) }}" class="adomx-action-btn" title="View & Respond">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No reviews found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $reviews->links() }}
    </div>
</div>
@endsection

