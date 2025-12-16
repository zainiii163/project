@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Analytics & Reporting</h2>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $stats['total_users'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Users</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $stats['total_students'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Students</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $stats['total_teachers'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Teachers</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $stats['total_courses'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Courses</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $stats['published_courses'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Published Courses</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $stats['total_enrollments'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Enrollments</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">${{ number_format($stats['total_revenue'], 2) }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Revenue</p>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-body">
            <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $stats['active_subscriptions'] }}</h3>
            <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Active Subscriptions</p>
        </div>
    </div>
</div>

<!-- Charts -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Revenue (Last 12 Months)</h3>
        </div>
        <div class="adomx-card-body">
            <canvas id="revenueChart" height="300"></canvas>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Enrollments (Last 12 Months)</h3>
        </div>
        <div class="adomx-card-body">
            <canvas id="enrollmentChart" height="300"></canvas>
        </div>
    </div>
</div>

<!-- Top Courses -->
<div class="adomx-card" style="margin-bottom: 30px;">
    <div class="adomx-card-header">
        <h3>Top Courses by Enrollment</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
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
                            <td><strong>{{ $course->title }}</strong></td>
                            <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                            <td>{{ $course->students_count }}</td>
                            <td>
                                @if($course->reviews_avg_rating)
                                    {{ number_format($course->reviews_avg_rating, 1) }}/5
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Recent Orders</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>{{ substr($order->id, 0, 8) }}...</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="adomx-status-badge adomx-status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueData->pluck('month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($revenueData->pluck('revenue')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Enrollment Chart
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(enrollmentCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($enrollmentData->pluck('month')) !!},
            datasets: [{
                label: 'Enrollments',
                data: {!! json_encode($enrollmentData->pluck('enrollments')) !!},
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

