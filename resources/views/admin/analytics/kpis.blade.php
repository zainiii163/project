@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Key Performance Indicators (KPIs)</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.analytics.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Analytics
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Students KPIs -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Students KPIs</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">{{ $kpis['students']['total'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Total Students</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">{{ $kpis['students']['active'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Active Students</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--info-color);">{{ $kpis['students']['new_this_month'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">New This Month</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--warning-color);">{{ $kpis['students']['completion_rate'] }}%</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Completion Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teachers KPIs -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Teachers KPIs</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">{{ $kpis['teachers']['total'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Total Teachers</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">{{ $kpis['teachers']['active'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Active Teachers</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--info-color);">{{ $kpis['teachers']['new_this_month'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">New This Month</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--warning-color);">{{ $kpis['teachers']['avg_rating'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Avg Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses KPIs -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Courses KPIs</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">{{ $kpis['courses']['total'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Total Courses</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">{{ $kpis['courses']['published'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Published</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--info-color);">{{ $kpis['courses']['new_this_month'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">New This Month</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--warning-color);">{{ $kpis['courses']['avg_enrollments'] }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Avg Enrollments</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue KPIs -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Revenue KPIs</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">${{ number_format($kpis['revenue']['total'], 2) }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Total Revenue</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">${{ number_format($kpis['revenue']['this_month'], 2) }}</div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">This Month</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color); grid-column: 1 / -1;">
                        <div style="font-size: 32px; font-weight: bold; color: {{ $kpis['revenue']['growth_rate'] >= 0 ? 'var(--success-color)' : 'var(--danger-color)' }};">
                            {{ $kpis['revenue']['growth_rate'] >= 0 ? '+' : '' }}{{ $kpis['revenue']['growth_rate'] }}%
                        </div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">Growth Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

