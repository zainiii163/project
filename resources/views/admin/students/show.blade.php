@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Student Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.students.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        @if($student->status == 'suspended')
            <form action="{{ route('admin.students.activate', $student) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="adomx-btn adomx-btn-success">
                    <i class="fas fa-check"></i> Activate
                </button>
            </form>
        @else
            <form action="{{ route('admin.students.suspend', $student) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="adomx-btn adomx-btn-warning">
                    <i class="fas fa-ban"></i> Suspend
                </button>
            </form>
        @endif
    </div>
</div>

<div class="adomx-row">
    <!-- Student Info Card -->
    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Student Information</h3>
            </div>
            <div class="adomx-card-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&size=150&background=10b981&color=fff' }}" 
                         alt="{{ $student->name }}" 
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                </div>
                <table class="adomx-info-table">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $student->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td><span class="adomx-status-badge adomx-status-{{ $student->status ?? 'active' }}">{{ ucfirst($student->status ?? 'active') }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Registered:</strong></td>
                        <td>{{ $student->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Enrollment Stats -->
        <div class="adomx-card" style="margin-top: 20px;">
            <div class="adomx-card-header">
                <h3>Enrollment Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="text-align: center; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">{{ $enrollmentStats['total_enrollments'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Total</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--success-color);">{{ $enrollmentStats['completed_courses'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Completed</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--info-color);">{{ $enrollmentStats['in_progress'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">In Progress</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: var(--card-bg); border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: var(--warning-color);">{{ $enrollmentStats['average_progress'] }}%</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Avg Progress</div>
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
                <button class="adomx-tab-btn" onclick="showTab('quizzes')">Quiz Performance</button>
                <button class="adomx-tab-btn" onclick="showTab('payments')">Payments</button>
                <button class="adomx-tab-btn" onclick="showTab('activity')">Activity</button>
            </div>

            <!-- Courses Tab -->
            <div id="courses" class="adomx-tab-content active">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Enrolled</th>
                                <th>Progress</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->courses as $course)
                                <tr>
                                    <td><strong>{{ $course->title }}</strong></td>
                                    <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                                    <td>{{ $course->pivot->enrolled_at ? $course->pivot->enrolled_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="adomx-progress-bar">
                                            <div class="adomx-progress-fill" style="width: {{ $course->pivot->progress ?? 0 }}%;"></div>
                                        </div>
                                        <span>{{ $course->pivot->progress ?? 0 }}%</span>
                                    </td>
                                    <td>{{ $course->pivot->completed_at ? $course->pivot->completed_at->format('M d, Y') : 'In Progress' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No enrollments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quiz Performance Tab -->
            <div id="quizzes" class="adomx-tab-content">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Quiz</th>
                                <th>Course</th>
                                <th>Avg Score</th>
                                <th>Max Score</th>
                                <th>Attempts</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quizPerformance as $perf)
                                <tr>
                                    <td>{{ $perf->quiz->title ?? 'N/A' }}</td>
                                    <td>{{ $perf->quiz->course->title ?? 'N/A' }}</td>
                                    <td>{{ number_format($perf->avg_score, 2) }}%</td>
                                    <td>{{ number_format($perf->max_score, 2) }}%</td>
                                    <td>{{ $perf->attempts }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No quiz attempts found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payments Tab -->
            <div id="payments" class="adomx-tab-content">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>#{{ $payment->id }}</td>
                                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td>${{ number_format($payment->total_price, 2) }}</td>
                                    <td><span class="adomx-status-badge adomx-status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $payment) }}" class="adomx-action-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($payment->status == 'completed')
                                            <form action="{{ route('admin.students.process-refund', $student) }}" method="POST" style="display: inline;" onsubmit="return confirm('Process refund for this order?')">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $payment->id }}">
                                                <button type="submit" class="adomx-action-btn" style="color: var(--warning-color);" title="Refund">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No payments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Tab -->
            <div id="activity" class="adomx-tab-content">
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                        <div style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Last Login</div>
                            <div style="font-size: 18px; font-weight: bold;">{{ $activity['last_login'] ? $activity['last_login']->format('M d, Y H:i') : 'Never' }}</div>
                        </div>
                        <div style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Quiz Attempts</div>
                            <div style="font-size: 18px; font-weight: bold;">{{ $activity['total_quiz_attempts'] }}</div>
                        </div>
                        <div style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Certificates</div>
                            <div style="font-size: 18px; font-weight: bold;">{{ $activity['certificates_earned'] }}</div>
                        </div>
                        <div style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Reviews</div>
                            <div style="font-size: 18px; font-weight: bold;">{{ $activity['reviews_submitted'] }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.students.activity', $student) }}" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-chart-line"></i> View Detailed Activity
                    </a>
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

