@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Student Feedback - {{ $student->name }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.students.show', $student) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Reviews -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Course Reviews</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedback['reviews'] as $review)
                                <tr>
                                    <td><strong>{{ $review->course->title ?? 'N/A' }}</strong></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 5px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#fbbf24' : '#d1d5db' }};"></i>
                                            @endfor
                                            <span>({{ $review->rating }})</span>
                                        </div>
                                    </td>
                                    <td style="max-width: 300px;">{{ Str::limit($review->comment, 100) }}</td>
                                    <td>{{ $review->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.reviews.show', $review) }}" class="adomx-action-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="adomx-table-empty">No reviews found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignment Feedback -->
    @if(isset($feedback['assignment_feedback']) && count($feedback['assignment_feedback']) > 0)
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Assignment Feedback</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Course</th>
                                <th>Grade</th>
                                <th>Feedback</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedback['assignment_feedback'] as $assignment)
                                <tr>
                                    <td><strong>{{ $assignment->title ?? 'N/A' }}</strong></td>
                                    <td>{{ $assignment->course->title ?? 'N/A' }}</td>
                                    <td>{{ $assignment->grade ?? 'N/A' }}</td>
                                    <td style="max-width: 300px;">{{ Str::limit($assignment->feedback ?? 'No feedback', 100) }}</td>
                                    <td>{{ $assignment->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

