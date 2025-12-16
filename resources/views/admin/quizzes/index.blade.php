@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Quiz Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.quizzes.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Quiz
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Quizzes</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.quizzes.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
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
                    <th>Pass Score</th>
                    <th>Time Limit</th>
                    <th>Max Attempts</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                    <tr>
                        <td><strong>{{ $quiz->title }}</strong></td>
                        <td>{{ $quiz->course->title ?? 'N/A' }}</td>
                        <td>{{ $quiz->questions->count() ?? 0 }}</td>
                        <td>{{ $quiz->pass_score }}%</td>
                        <td>{{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'Unlimited' }}</td>
                        <td>{{ $quiz->max_attempts ?? 'Unlimited' }}</td>
                        <td>{{ $quiz->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="adomx-action-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
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
                        <td colspan="8" class="adomx-table-empty">No quizzes found</td>
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

