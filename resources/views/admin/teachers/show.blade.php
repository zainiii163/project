@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Teacher Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.teachers.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        @if($teacher->status == 'suspended')
            <form action="{{ route('admin.teachers.approve', $teacher) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="adomx-btn adomx-btn-success">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
        @else
            <form action="{{ route('admin.teachers.suspend', $teacher) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="adomx-btn adomx-btn-warning">
                    <i class="fas fa-ban"></i> Suspend
                </button>
            </form>
        @endif
    </div>
</div>

<div class="adomx-row">
    <!-- Teacher Info Card -->
    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Teacher Information</h3>
            </div>
            <div class="adomx-card-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ $teacher->profile_picture ? asset('storage/' . $teacher->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->name) . '&size=150&background=4f46e5&color=fff' }}" 
                         alt="{{ $teacher->name }}" 
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                </div>
                <table class="adomx-info-table">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $teacher->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $teacher->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td><span class="adomx-status-badge adomx-status-{{ $teacher->status ?? 'active' }}">{{ ucfirst($teacher->status ?? 'active') }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Registered:</strong></td>
                        <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="adomx-card" style="margin-top: 20px;">
            <div class="adomx-card-header">
                <h3>Performance Metrics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="text-align: center; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">{{ $metrics['total_courses'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Total Courses</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--success-color);">{{ $metrics['published_courses'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Published</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--info-color);">{{ $metrics['total_students'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Total Students</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--warning-color);">{{ number_format($metrics['average_rating'], 1) }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Avg Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Tabs -->
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-tabs">
                <button class="adomx-tab-btn active" onclick="showTab('courses')">Courses</button>
                <button class="adomx-tab-btn" onclick="showTab('revenue')">Revenue</button>
                <button class="adomx-tab-btn" onclick="showTab('performance')">Performance</button>
            </div>

            <!-- Courses Tab -->
            <div id="courses" class="adomx-tab-content active">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Enrollments</th>
                                <th>Rating</th>
                                <th>Reviews</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coursePerformance as $perf)
                                <tr>
                                    <td><strong>{{ $perf['course']->title }}</strong></td>
                                    <td>{{ $perf['course']->category->name ?? 'Uncategorized' }}</td>
                                    <td>{{ $perf['enrollments'] }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                                            <span>{{ $perf['rating'] }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $perf['reviews_count'] }}</td>
                                    <td>
                                        <span class="adomx-status-badge adomx-status-{{ $perf['course']->status }}">
                                            {{ ucfirst($perf['course']->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No courses found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Revenue Tab -->
            <div id="revenue" class="adomx-tab-content">
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
                        <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Total Revenue</div>
                            <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">${{ number_format($revenue['total_revenue'], 2) }}</div>
                        </div>
                        <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Teacher Earnings</div>
                            <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">${{ number_format($revenue['teacher_earnings'], 2) }}</div>
                        </div>
                        <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Platform Commission</div>
                            <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">${{ number_format($revenue['platform_commission'], 2) }}</div>
                        </div>
                        <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Pending Payouts</div>
                            <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">${{ number_format($revenue['pending_payouts'], 2) }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.teachers.payouts', $teacher) }}" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-dollar-sign"></i> Manage Payouts
                    </a>
                </div>
            </div>

            <!-- Performance Tab -->
            <div id="performance" class="adomx-tab-content">
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Total Enrollments</div>
                            <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">{{ $metrics['total_enrollments'] }}</div>
                        </div>
                        <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Completion Rate</div>
                            <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">{{ $metrics['completion_rate'] }}%</div>
                        </div>
                        <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Engagement Score</div>
                            <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">{{ $metrics['engagement_score'] }}</div>
                        </div>
                        <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Total Reviews</div>
                            <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">{{ $metrics['total_reviews'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    document.querySelectorAll('.adomx-tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.querySelectorAll('.adomx-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

<style>
.adomx-tabs {
    display: flex;
    border-bottom: 2px solid var(--border-color);
    margin-bottom: 20px;
}

.adomx-tab-btn {
    padding: 12px 24px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    color: var(--text-secondary);
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all 0.3s;
}

.adomx-tab-btn:hover {
    color: var(--primary-color);
}

.adomx-tab-btn.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.adomx-tab-content {
    display: none;
}

.adomx-tab-content.active {
    display: block;
}

.adomx-info-table {
    width: 100%;
}

.adomx-info-table td {
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color);
}

.adomx-info-table td:first-child {
    width: 40%;
    color: var(--text-secondary);
}
</style>
@endsection

