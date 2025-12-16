@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Quizzes</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Available Quizzes</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('student.quizzes.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
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
                    <th>Quiz</th>
                    <th>Course</th>
                    <th>Pass Score</th>
                    <th>My Attempts</th>
                    <th>Best Score</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                    <tr>
                        <td><strong>{{ $quiz->title }}</strong></td>
                        <td>{{ $quiz->course->title ?? 'N/A' }}</td>
                        <td>{{ $quiz->pass_score }}%</td>
                        <td>{{ $quiz->attempts->count() }}</td>
                        <td>
                            @if($quiz->attempts->count() > 0)
                                {{ $quiz->attempts->max('score') }}%
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('student.quizzes.attempt', $quiz) }}" class="adomx-btn adomx-btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-play"></i>
                                Take Quiz
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No quizzes available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $quizzes->links() }}
    </div>
</div>

<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-header">
        <h3>My Quiz Attempts</h3>
    </div>
    <div class="adomx-card-body">
        <a href="{{ route('student.quizzes.attempts') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-history"></i>
            View All Attempts
        </a>
    </div>
</div>
@endsection

