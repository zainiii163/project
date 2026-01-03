@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Review Course: {{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.moderation.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Course Details</h3>
            </div>
            <div class="adomx-card-body">
                <table class="table">
                    <tr>
                        <th>Title:</th>
                        <td>{{ $course->title }}</td>
                    </tr>
                    <tr>
                        <th>Teacher:</th>
                        <td>{{ $course->teacher->name }}</td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td>{{ $course->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Price:</th>
                        <td>${{ number_format($course->price, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Level:</th>
                        <td>{{ ucfirst($course->level) }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge badge-{{ $course->status === 'pending_approval' ? 'warning' : 'danger' }}">
                                {{ ucfirst(str_replace('_', ' ', $course->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{!! nl2br(e($course->description)) !!}</td>
                    </tr>
                    @if($course->rejection_reason)
                    <tr>
                        <th>Previous Rejection Reason:</th>
                        <td class="text-danger">{{ $course->rejection_reason }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="adomx-card mt-4">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Lessons ({{ $course->lessons->count() }})</h3>
            </div>
            <div class="adomx-card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->lessons as $lesson)
                            <tr>
                                <td>{{ $lesson->order }}</td>
                                <td>{{ $lesson->title }}</td>
                                <td>{{ ucfirst($lesson->type) }}</td>
                                <td>
                                    <span class="badge badge-{{ $lesson->status === 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($lesson->status ?? 'draft') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="adomx-card mt-4">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Quizzes ({{ $course->quizzes->count() }})</h3>
            </div>
            <div class="adomx-card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Questions</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->quizzes as $quiz)
                            <tr>
                                <td>{{ $quiz->title }}</td>
                                <td>{{ $quiz->questions->count() }}</td>
                                <td>
                                    <span class="badge badge-{{ $quiz->status === 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($quiz->status ?? 'draft') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Moderation Actions</h3>
            </div>
            <div class="adomx-card-body">
                @if($course->status === 'pending_approval')
                <form action="{{ route('admin.moderation.approve-course', $course) }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Approve Course
                    </button>
                </form>

                <form action="{{ route('admin.moderation.reject-course', $course) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to reject this course?')">
                        <i class="fas fa-times"></i> Reject Course
                    </button>
                </form>
                @elseif($course->status === 'rejected')
                <div class="alert alert-warning">
                    <strong>Rejected</strong>
                    <p>{{ $course->rejection_reason }}</p>
                </div>
                <form action="{{ route('admin.moderation.approve-course', $course) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Approve Anyway
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

