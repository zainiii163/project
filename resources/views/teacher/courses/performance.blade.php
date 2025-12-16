@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Student Performance - {{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.courses.show', $course) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Course
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $stats['total_students'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Students</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $stats['completed_students'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Completed</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ number_format($stats['avg_progress'], 1) }}%</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Avg Progress</p>
        </div>
    </div>
</div>

<div class="adomx-card" style="margin-bottom: 30px;">
    <div class="adomx-card-header">
        <h3>Quiz Performance</h3>
    </div>
    <div class="adomx-card-body">
        @if($quizPerformance->count() > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>Quiz</th>
                            <th>Total Attempts</th>
                            <th>Avg Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quizPerformance as $quiz)
                            <tr>
                                <td><strong>{{ $quiz->title }}</strong></td>
                                <td>{{ $quiz->attempts->count() }}</td>
                                <td>
                                    @php
                                        $avgScore = $quiz->attempts->avg('score') ?? 0;
                                    @endphp
                                    {{ number_format($avgScore, 1) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="text-align: center; color: var(--text-secondary); padding: 20px;">No quiz data available</p>
        @endif
    </div>
</div>
@endsection

