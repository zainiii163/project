@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Quizzes</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All My Quizzes</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('teacher.quizzes.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search quizzes..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
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
                    <th>Title</th>
                    <th>Course</th>
                    <th>Questions</th>
                    <th>Attempts</th>
                    <th>Pass Score</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                    <tr>
                        <td><strong>{{ $quiz->title }}</strong></td>
                        <td>{{ $quiz->course->title ?? 'N/A' }}</td>
                        <td>{{ $quiz->questions->count() }}</td>
                        <td>{{ $quiz->attempts->count() }}</td>
                        <td>{{ $quiz->pass_score }}%</td>
                        <td>
                            <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="adomx-action-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No quizzes found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection

