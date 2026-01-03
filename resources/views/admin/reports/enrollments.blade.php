@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Enrollments Report</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.reports.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card mb-4">
    <div class="adomx-card-body">
        <form method="GET" action="{{ route('admin.reports.enrollments') }}" class="row g-3">
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
                    <a href="{{ route('admin.reports.enrollments') }}" class="btn btn-secondary">Reset</a>
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
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Enrollments</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $summary['completed'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Completed</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $summary['in_progress'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">In Progress</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $summary['avg_progress'] }}%</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Avg Progress</p>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Enrollments Data</h3>
        <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="report_type" value="enrollments">
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
                        <th>Course</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Enrolled At</th>
                        <th>Progress</th>
                        <th>Completed At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->course_title }}</td>
                        <td>{{ $enrollment->student_name }}</td>
                        <td>{{ $enrollment->student_email }}</td>
                        <td>{{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}</td>
                        <td>{{ number_format($enrollment->progress ?? 0, 1) }}%</td>
                        <td>{{ $enrollment->completed_at ? \Carbon\Carbon::parse($enrollment->completed_at)->format('M d, Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No enrollments found for the selected period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

