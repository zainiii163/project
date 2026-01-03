@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Reports & Analytics</h2>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Generate Reports</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <a href="{{ route('admin.reports.enrollments') }}" class="adomx-btn" style="padding: 30px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 15px; background: var(--card-bg); border: 1px solid var(--border-color);">
                        <i class="fas fa-user-graduate" style="font-size: 48px; color: var(--primary-color);"></i>
                        <div>
                            <h4 style="margin: 0;">Enrollments Report</h4>
                            <p style="margin: 5px 0 0 0; color: var(--text-secondary); font-size: 14px;">Track student enrollments</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.revenue') }}" class="adomx-btn" style="padding: 30px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 15px; background: var(--card-bg); border: 1px solid var(--border-color);">
                        <i class="fas fa-dollar-sign" style="font-size: 48px; color: var(--success-color);"></i>
                        <div>
                            <h4 style="margin: 0;">Revenue Report</h4>
                            <p style="margin: 5px 0 0 0; color: var(--text-secondary); font-size: 14px;">Financial analytics</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.users') }}" class="adomx-btn" style="padding: 30px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 15px; background: var(--card-bg); border: 1px solid var(--border-color);">
                        <i class="fas fa-users" style="font-size: 48px; color: var(--info-color);"></i>
                        <div>
                            <h4 style="margin: 0;">Users Report</h4>
                            <p style="margin: 5px 0 0 0; color: var(--text-secondary); font-size: 14px;">User statistics</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.courses') }}" class="adomx-btn" style="padding: 30px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 15px; background: var(--card-bg); border: 1px solid var(--border-color);">
                        <i class="fas fa-book" style="font-size: 48px; color: var(--warning-color);"></i>
                        <div>
                            <h4 style="margin: 0;">Courses Report</h4>
                            <p style="margin: 5px 0 0 0; color: var(--text-secondary); font-size: 14px;">Course performance</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

