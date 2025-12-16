@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>User Analytics</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.analytics.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Analytics
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $userStats['total'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Users</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $userStats['students'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Students</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $userStats['teachers'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Teachers</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $userStats['admins'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Admins</p>
        </div>
    </div>
</div>

<div class="adomx-card" style="margin-bottom: 30px;">
    <div class="adomx-card-header">
        <h3>User Registration Trend (Last 12 Months)</h3>
    </div>
    <div class="adomx-card-body">
        <canvas id="registrationChart" height="300"></canvas>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Recent Users</h3>
    </div>
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUsers as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $user->role }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="adomx-table-empty">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    const registrationCtx = document.getElementById('registrationChart').getContext('2d');
    new Chart(registrationCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($registrationData->pluck('month')) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($registrationData->pluck('count')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
@endsection

