@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>AI Insights</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.analytics.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Analytics
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Student Engagement -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Student Engagement Analysis</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">{{ number_format($insights['student_engagement']['highly_engaged']) }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">Highly Engaged</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">{{ number_format($insights['student_engagement']['moderately_engaged']) }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">Moderate</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--danger-color);">{{ number_format($insights['student_engagement']['low_engagement']) }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">Low Engagement</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dropout Prediction -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Dropout Prediction</h3>
            </div>
            <div class="adomx-card-body">
                @if(count($insights['dropout_prediction']['at_risk_students']) > 0)
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">At-Risk Students</div>
                        <div style="font-size: 24px; font-weight: bold; color: var(--danger-color);">
                            {{ count($insights['dropout_prediction']['at_risk_students']) }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Risk Factors</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            @foreach($insights['dropout_prediction']['risk_factors'] as $factor)
                                <span style="padding: 5px 10px; background: var(--danger-color); color: white; border-radius: 15px; font-size: 12px;">
                                    {{ ucfirst(str_replace('_', ' ', $factor)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 20px; color: var(--text-secondary);">
                        <i class="fas fa-check-circle" style="font-size: 48px; color: var(--success-color); margin-bottom: 10px;"></i>
                        <p>No at-risk students detected</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Revenue Forecast -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Revenue Forecast</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">${{ number_format($insights['revenue_forecast']['next_month'], 2) }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">Next Month</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 24px; font-weight: bold; color: var(--success-color);">${{ number_format($insights['revenue_forecast']['next_quarter'], 2) }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 5px;">Next Quarter</div>
                    </div>
                </div>
                <div style="margin-top: 15px; padding: 15px; background: rgba(59, 130, 246, 0.1); border-radius: 8px; font-size: 12px; color: var(--text-secondary);">
                    <i class="fas fa-info-circle" style="margin-right: 5px;"></i>
                    Forecasts are based on historical data and trends
                </div>
            </div>
        </div>
    </div>

    <!-- Course Recommendations -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Recommended Courses</h3>
            </div>
            <div class="adomx-card-body">
                @if(count($insights['course_recommendations']) > 0)
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($insights['course_recommendations'] as $course)
                            <div style="padding: 15px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong>{{ $course->title }}</strong>
                                        <div style="font-size: 12px; color: var(--text-secondary);">
                                            {{ $course->students_count }} students enrolled
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.courses.index') }}" class="adomx-action-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 20px; color: var(--text-secondary);">
                        <p>No recommendations available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

