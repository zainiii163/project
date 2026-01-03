@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Content Moderation</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Pending Approval</h5>
                <h2>{{ $stats['pending'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Approved</h5>
                <h2>{{ $stats['approved'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Rejected</h5>
                <h2>{{ $stats['rejected'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->teacher->name }}</td>
                        <td>{{ $course->category->name ?? 'N/A' }}</td>
                        <td>
                            @if($course->status == 'pending_approval')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($course->status == 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($course->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.moderation.review', $course) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Review
                            </a>
                            @if($course->status == 'pending_approval')
                            <form action="{{ route('admin.moderation.approve', $course) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No courses pending moderation.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $courses->links() }}
    </div>
</div>
@endsection

