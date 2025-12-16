@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Admin Dashboard</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
</div>

<div class="adomx-row">
    <!-- Statistics Cards -->
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Users</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">{{ $stats['total_users'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">
                            Today: {{ $stats['today_visitors'] }}
                        </div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(79, 70, 229, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users" style="font-size: 24px; color: var(--primary-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Courses</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">{{ $stats['total_courses'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">
                            Published: {{ $stats['published_courses'] }}
                        </div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book" style="font-size: 24px; color: var(--success-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Revenue</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--info-color);">${{ number_format($stats['total_revenue'], 2) }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">
                            Today: ${{ number_format($stats['today_revenue'], 2) }}
                        </div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-dollar-sign" style="font-size: 24px; color: var(--info-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Total Enrollments</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--warning-color);">{{ $stats['total_enrollments'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">
                            Today: {{ $stats['today_products_sold'] }}
                        </div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-graduate" style="font-size: 24px; color: var(--warning-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>User Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">{{ $stats['total_teachers'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Teachers</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--success-color);">{{ $stats['total_students'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Students</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Order Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--warning-color);">{{ $stats['pending_orders'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Pending Orders</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--info-color);">{{ $stats['today_orders'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Today's Orders</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Recent Courses</h3>
                <a href="{{ route('admin.courses.index') }}" class="adomx-btn adomx-btn-primary" style="padding: 8px 15px; font-size: 14px;">
                    View All
                </a>
            </div>
            <div class="adomx-card-body">
                @if($recent_courses->count() > 0)
                    <div class="adomx-table-container">
                        <table class="adomx-table">
                            <thead>
                                <tr>
                                    <th>Course Title</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_courses as $course)
                                    <tr>
                                        <td><strong>{{ $course->title }}</strong></td>
                                        <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="adomx-status-badge adomx-status-{{ $course->status }}">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.courses.index') }}" class="adomx-action-btn" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align: center; padding: 20px; color: var(--text-secondary);">No courses found</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="adomx-btn adomx-btn-primary" style="padding: 8px 15px; font-size: 14px;">
                    View All
                </a>
            </div>
            <div class="adomx-card-body">
                @if($recent_orders->count() > 0)
                    <div class="adomx-table-container">
                        <table class="adomx-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($order->total_price, 2) }}</td>
                                        <td>
                                            <span class="adomx-status-badge adomx-status-{{ $order->status }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="adomx-action-btn" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align: center; padding: 20px; color: var(--text-secondary);">No orders found</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Revenue Chart (12 Months) -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Revenue Trend (Last 12 Months)</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Market Trends & Daily Sales -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Market Trends</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">{{ number_format($marketTrends['new_customer']) }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">New Customers (30d)</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">${{ number_format($marketTrends['revenue'], 2) }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Total Revenue</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">{{ number_format($marketTrends['product_sold']) }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Products Sold</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">${{ number_format($marketTrends['profit'], 2) }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Estimated Profit</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Sales Report -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Daily Sales Report (Last 7 Days)</h3>
            </div>
            <div class="adomx-card-body">
                @if(count($dailySales) > 0)
                    <div class="adomx-table-container">
                        <table class="adomx-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Orders</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailySales as $sale)
                                    <tr>
                                        <td>{{ $sale['date'] }}</td>
                                        <td>{{ $sale['client'] }}</td>
                                        <td>{{ $sale['detail'] }}</td>
                                        <td><strong>${{ number_format($sale['payment'], 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align: center; padding: 20px; color: var(--text-secondary);">No sales data available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Recent Transactions</h3>
                <a href="{{ route('admin.orders.index') }}" class="adomx-btn adomx-btn-primary" style="padding: 8px 15px; font-size: 14px;">
                    View All
                </a>
            </div>
            <div class="adomx-card-body">
                @if($recentTransactions->count() > 0)
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
                                @foreach($recentTransactions->take(10) as $transaction)
                                    <tr>
                                        <td>#{{ $transaction->id }}</td>
                                        <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($transaction->total_price, 2) }}</td>
                                        <td>
                                            <span class="adomx-status-badge adomx-status-{{ $transaction->status }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->order_date ? \Carbon\Carbon::parse($transaction->order_date)->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align: center; padding: 20px; color: var(--text-secondary);">No transactions found</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Quick Access</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <a href="{{ route('admin.users.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none;">
                        <i class="fas fa-users" style="font-size: 28px; color: var(--primary-color);"></i>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.courses.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none;">
                        <i class="fas fa-book" style="font-size: 28px; color: var(--success-color);"></i>
                        <span>Courses</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none;">
                        <i class="fas fa-shopping-cart" style="font-size: 28px; color: var(--info-color);"></i>
                        <span>Orders</span>
                    </a>
                    <a href="{{ route('admin.payments.coupons.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none;">
                        <i class="fas fa-tag" style="font-size: 28px; color: var(--warning-color);"></i>
                        <span>Coupons</span>
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none;">
                        <i class="fas fa-chart-bar" style="font-size: 28px; color: var(--primary-color);"></i>
                        <span>Analytics</span>
                    </a>
                    <a href="{{ route('admin.announcements.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none;">
                        <i class="fas fa-bullhorn" style="font-size: 28px; color: var(--success-color);"></i>
                        <span>Announcements</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Revenue Chart (12 Months)
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueData = @json($revenueData);
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month),
                datasets: [{
                    label: 'Revenue ($)',
                    data: revenueData.map(item => parseFloat(item.revenue)),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: $' + parseFloat(context.parsed.y).toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(0);
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection

