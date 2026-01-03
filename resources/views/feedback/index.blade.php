@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>My Feedback</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('feedback.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Submit Feedback
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Course</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $feedback)
                        <tr>
                            <td>{{ $feedback->subject }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($feedback->type) }}</span></td>
                            <td>{{ $feedback->course ? $feedback->course->title : 'N/A' }}</td>
                            <td>
                                @if($feedback->rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($feedback->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($feedback->status == 'reviewed')
                                    <span class="badge badge-success">Reviewed</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($feedback->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('feedback.show', $feedback) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No feedback submitted yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>
@endsection

