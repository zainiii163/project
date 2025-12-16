@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Revenue Report</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payments.revenue-report.export', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-download"></i>
            Export CSV
        </a>
        <a href="{{ route('admin.payments.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Date Filter -->
<div class="adomx-card" style="margin-bottom: 20px;">
    <div class="adomx-card-body">
        <form action="{{ route('admin.payments.revenue-report') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="date" name="date_from" class="adomx-input" value="{{ $dateFrom }}" required>
            <span>to</span>
            <input type="date" name="date_to" class="adomx-input" value="{{ $dateTo }}" required>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="adomx-row" style="margin-bottom: 20px;">
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Revenue Summary</h3>
            </div>
            <div class="adomx-card-body">
                <div style="text-align: center; padding: 30px;">
                    <div style="font-size: 48px; font-weight: bold; color: var(--primary-color); margin-bottom: 10px;">
                        ${{ number_format($totalRevenue, 2) }}
                    </div>
                    <div style="font-size: 14px; color: var(--text-secondary);">
                        Total Revenue from {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="adomx-row">
    <!-- Revenue by Course -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Revenue by Course</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Sales</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenueByCourse as $item)
                                <tr>
                                    <td><strong>{{ $item->title }}</strong></td>
                                    <td>{{ $item->sales }}</td>
                                    <td><strong>${{ number_format($item->revenue, 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="adomx-table-empty">No revenue data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by Teacher -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Revenue by Teacher</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Teacher</th>
                                <th>Sales</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenueByTeacher as $item)
                                <tr>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td>{{ $item->sales }}</td>
                                    <td><strong>${{ number_format($item->revenue, 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="adomx-table-empty">No revenue data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Daily Revenue Breakdown</h3>
            </div>
            <div class="adomx-card-body">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyRevenue as $day)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($day['date'])->format('M d, Y') }}</td>
                                    <td><strong>${{ number_format($day['revenue'], 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="adomx-table-empty">No revenue data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

