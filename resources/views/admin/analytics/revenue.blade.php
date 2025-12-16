@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Revenue Analytics</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.analytics.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Analytics
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Monthly Revenue (Last 12 Months)</h3>
        </div>
        <div class="adomx-card-body">
            <canvas id="monthlyRevenueChart" height="300"></canvas>
        </div>
    </div>
    <div class="adomx-card">
        <div class="adomx-card-header">
            <h3>Daily Revenue (Last 30 Days)</h3>
        </div>
        <div class="adomx-card-body">
            <canvas id="dailyRevenueChart" height="300"></canvas>
        </div>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">Monthly Revenue Details</h3>
    </div>
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Revenue</th>
                    <th>Orders</th>
                </tr>
            </thead>
            <tbody>
                @forelse($revenueData as $data)
                    <tr>
                        <td>{{ $data->month }}</td>
                        <td>${{ number_format($data->revenue, 2) }}</td>
                        <td>{{ $data->orders }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="adomx-table-empty">No revenue data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // Monthly Revenue Chart
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyCtx, {
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

    // Daily Revenue Chart
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
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

