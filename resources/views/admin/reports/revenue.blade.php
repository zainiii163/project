@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Revenue Report</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.reports.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card mb-4">
    <div class="adomx-card-body">
        <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3">
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
                    <a href="{{ route('admin.reports.revenue') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="adomx-row mb-4">
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">${{ number_format($summary['total_revenue'], 2) }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Revenue</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--primary-color);">{{ $summary['total_orders'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Total Orders</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">${{ number_format($summary['avg_order_value'], 2) }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Avg Order Value</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--warning-color);">{{ $summary['unique_customers'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Unique Customers</p>
            </div>
        </div>
    </div>
</div>

<div class="adomx-row mb-4">
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Revenue by Course</h3>
            </div>
            <div class="adomx-card-body">
                <div class="table-responsive">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Revenue</th>
                                <th>Orders</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenueByCourse as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>${{ number_format($course->total_revenue, 2) }}</td>
                                <td>{{ $course->total_orders }}</td>
                                <td>{{ $course->total_sales }}</td>
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
                <h3 class="adomx-card-title">Revenue by Date</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Order Details</h3>
        <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="report_type" value="revenue">
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
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ substr($order->id, 0, 8) }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->order_date->format('M d, Y') }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No orders found for the selected period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueData = @json($revenueByDate);
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.date),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(item => item.revenue),
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
</script>
@endpush
@endsection

