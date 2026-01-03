@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('student.progress.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h2>{{ $course->title }}</h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <h3 style="font-size: 48px; margin: 0; color: var(--primary-color);">{{ $overallProgress['overall_progress'] }}%</h3>
                        <p class="text-muted">Overall Progress</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h3 style="font-size: 48px; margin: 0; color: var(--success-color);">{{ $overallProgress['completed_lessons'] }}/{{ $overallProgress['total_lessons'] }}</h3>
                        <p class="text-muted">Lessons Completed</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h3 style="font-size: 48px; margin: 0; color: var(--info-color);">{{ $overallProgress['passed_quizzes'] }}/{{ $overallProgress['total_quizzes'] }}</h3>
                        <p class="text-muted">Quizzes Passed</p>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <strong>Lesson Progress:</strong>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $overallProgress['lesson_progress'] }}%;" aria-valuenow="{{ $overallProgress['lesson_progress'] }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $overallProgress['lesson_progress'] }}%
                    </div>
                </div>
            </div>
            <div>
                <strong>Quiz Progress:</strong>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $overallProgress['quiz_progress'] }}%;" aria-valuenow="{{ $overallProgress['quiz_progress'] }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $overallProgress['quiz_progress'] }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lessons Progress -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Lessons Progress</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Lesson</th>
                            <th>Status</th>
                            <th>Last Accessed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lessonProgress as $data)
                        <tr>
                            <td>
                                <strong>{{ $data['lesson']->title }}</strong>
                                @if($data['lesson']->is_preview)
                                <span class="badge badge-info">Preview</span>
                                @endif
                            </td>
                            <td>
                                @if($data['is_completed'])
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Completed
                                </span>
                                @elseif($data['progress'])
                                <span class="badge badge-warning">
                                    <i class="fas fa-clock"></i> In Progress
                                </span>
                                @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-circle"></i> Not Started
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($data['last_accessed'])
                                {{ $data['last_accessed']->format('M d, Y H:i') }}
                                @else
                                <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('lessons.show', $data['lesson']) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-play"></i> {{ $data['is_completed'] ? 'Review' : 'Start' }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No lessons in this course.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quizzes Progress -->
    @if($quizProgress)
    <div class="card">
        <div class="card-header">
            <h5>Quizzes Progress</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Quiz</th>
                            <th>Attempts</th>
                            <th>Best Score</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizProgress as $data)
                        <tr>
                            <td><strong>{{ $data['quiz']->title }}</strong></td>
                            <td>{{ $data['attempts']->count() }}</td>
                            <td>
                                @if($data['best_score'] !== null)
                                <strong>{{ number_format($data['best_score'], 1) }}%</strong>
                                @else
                                <span class="text-muted">Not attempted</span>
                                @endif
                            </td>
                            <td>
                                @if($data['is_passed'])
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Passed
                                </span>
                                @elseif($data['best_score'] !== null)
                                <span class="badge badge-warning">
                                    <i class="fas fa-times-circle"></i> Not Passed
                                </span>
                                @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-circle"></i> Not Attempted
                                </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('quizzes.show', $data['quiz']) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-question-circle"></i> Take Quiz
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No quizzes in this course.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

