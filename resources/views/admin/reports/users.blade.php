@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Users Report</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.reports.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card mb-4">
    <div class="adomx-card-body">
        <form method="GET" action="{{ route('admin.reports.users') }}" class="row g-3">
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
                    <a href="{{ route('admin.reports.users') }}" class="btn btn-secondary">Reset</a>
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
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Users</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $summary['students'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Students</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $summary['teachers'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Teachers</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $summary['active'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Active Users</p>
            </div>
        </div>
    </div>
</div>

<div class="adomx-row mb-4">
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Users by Role</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="roleChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Registration Trend</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="registrationChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Users List</h3>
        <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="report_type" value="users">
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Courses</th>
                        <th>Certificates</th>
                        <th>Orders</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($user->role) }}</span></td>
                        <td><span class="badge badge-{{ $user->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($user->status) }}</span></td>
                        <td>{{ $user->courses_count ?? 0 }}</td>
                        <td>{{ $user->certificates_count ?? 0 }}</td>
                        <td>{{ $user->orders_count ?? 0 }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No users found for the selected period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Users by Role Chart
    const roleData = @json($usersByRole);
    const roleCtx = document.getElementById('roleChart');
    if (roleCtx) {
        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: roleData.map(item => ucfirst(item.role)),
                datasets: [{
                    data: roleData.map(item => item.count),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ]
                }]
            }
        });
    }

    // Registration Trend Chart
    const regData = @json($usersByDate);
    const regCtx = document.getElementById('registrationChart');
    if (regCtx) {
        new Chart(regCtx, {
            type: 'bar',
            data: {
                labels: regData.map(item => item.date),
                datasets: [{
                    label: 'Registrations',
                    data: regData.map(item => item.count),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)'
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

