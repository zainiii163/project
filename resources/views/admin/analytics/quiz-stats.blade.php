@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Quiz Statistics</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.analytics.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Analytics
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Quiz Performance Statistics</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Quiz Title</th>
                    <th>Course</th>
                    <th>Total Attempts</th>
                    <th>Average Score</th>
                    <th>Max Score</th>
                    <th>Min Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizStats as $stat)
                    <tr>
                        <td><strong>{{ $stat->title }}</strong></td>
                        <td>{{ $stat->course_title ?? 'N/A' }}</td>
                        <td>{{ $stat->total_attempts ?? 0 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="adomx-progress-bar" style="width: 100px;">
                                    <div class="adomx-progress-fill" style="width: {{ $stat->avg_score ?? 0 }}%;"></div>
                                </div>
                                <span>{{ number_format($stat->avg_score ?? 0, 1) }}%</span>
                            </div>
                        </td>
                        <td style="color: var(--success-color); font-weight: bold;">{{ number_format($stat->max_score ?? 0, 1) }}%</td>
                        <td style="color: var(--danger-color);">{{ number_format($stat->min_score ?? 0, 1) }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No quiz statistics available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $quizStats->links() }}
    </div>
</div>
@endsection

