@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Support Analytics</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.support.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="adomx-row mb-4">
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $stats['total_tickets'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Tickets</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $stats['open_tickets'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Open Tickets</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $stats['resolved_tickets'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Resolved Tickets</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $stats['avg_resolution_time'] }}h</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Avg Resolution Time</p>
            </div>
        </div>
    </div>
</div>

<div class="adomx-row mb-4">
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Tickets by Category</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="categoryChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Tickets by Priority</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="priorityChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="adomx-row mb-4">
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Tickets by Status</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="statusChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Tickets Over Time</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="timeChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Tickets by Category
    const categoryData = @json($stats['tickets_by_category']);
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => ucfirst(item.category)),
                datasets: [{
                    data: categoryData.map(item => item.count),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ]
                }]
            }
        });
    }

    // Tickets by Priority
    const priorityData = @json($stats['tickets_by_priority']);
    const priorityCtx = document.getElementById('priorityChart');
    if (priorityCtx) {
        new Chart(priorityCtx, {
            type: 'bar',
            data: {
                labels: priorityData.map(item => ucfirst(item.priority)),
                datasets: [{
                    label: 'Tickets',
                    data: priorityData.map(item => item.count),
                    backgroundColor: [
                        'rgba(108, 117, 125, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Tickets by Status
    const statusData = @json($stats['tickets_by_status']);
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: statusData.map(item => ucfirst(item.status.replace('_', ' '))),
                datasets: [{
                    data: statusData.map(item => item.count),
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(108, 117, 125, 0.8)',
                        'rgba(33, 37, 41, 0.8)'
                    ]
                }]
            }
        });
    }

    // Tickets Over Time
    const timeData = @json($ticketsOverTime);
    const timeCtx = document.getElementById('timeChart');
    if (timeCtx) {
        new Chart(timeCtx, {
            type: 'line',
            data: {
                labels: timeData.map(item => item.month),
                datasets: [{
                    label: 'Opened',
                    data: timeData.map(item => item.opened),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Resolved',
                    data: timeData.map(item => item.resolved),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
@endpush
@endsection

