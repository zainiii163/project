@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Courses Report</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.reports.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card mb-4">
    <div class="adomx-card-body">
        <form method="GET" action="{{ route('admin.reports.courses') }}" class="row g-3">
            <div class="col-md-4">
                <label>Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label>Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.reports.courses') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="adomx-row mb-4">
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $summary['total'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Courses</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $summary['published'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Published</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $summary['draft'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Draft</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $summary['avg_enrollments'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Avg Enrollments</p>
            </div>
        </div>
    </div>
</div>

<div class="adomx-row mb-4">
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Top 10 Courses</h3>
            </div>
            <div class="adomx-card-body">
                <div class="table-responsive">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Enrollments</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCourses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                                <td>{{ $course->students_count }}</td>
                                <td>{{ number_format($course->reviews_avg_rating ?? 0, 1) }} ⭐</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Courses by Status</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="statusChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">All Courses</h3>
        <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="report_type" value="courses">
            <input type="hidden" name="format" value="csv">
            <input type="hidden" name="date_from" value="{{ $dateFrom }}">
            <input type="hidden" name="date_to" value="{{ $dateTo }}">
            <button type="submit" class="adomx-btn adomx-btn-primary">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </form>
    </div>
    <div class="adomx-card-body">
        <div class="table-responsive">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Teacher</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Enrollments</th>
                        <th>Rating</th>
                        <th>Lessons</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                        <td>{{ $course->category->name ?? 'Uncategorized' }}</td>
                        <td>${{ number_format($course->price, 2) }}</td>
                        <td><span class="badge badge-{{ $course->status === 'published' ? 'success' : 'warning' }}">{{ ucfirst($course->status) }}</span></td>
                        <td>{{ $course->students_count }}</td>
                        <td>{{ number_format($course->reviews_avg_rating ?? 0, 1) }} ⭐</td>
                        <td>{{ $course->lessons_count }}</td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No courses found for the selected period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $courses->links() }}
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statusData = @json($coursesByStatus);
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: statusData.map(item => ucfirst(item.status)),
                datasets: [{
                    data: statusData.map(item => item.count),
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(59, 130, 246, 0.8)'
                    ]
                }]
            }
        });
    }

    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
@endpush
@endsection

