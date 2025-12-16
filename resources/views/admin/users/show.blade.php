@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>User Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.users.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- User Info Card -->
    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>User Information</h3>
            </div>
            <div class="adomx-card-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=150&background=4f46e5&color=fff' }}" 
                         alt="{{ $user->name }}" 
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                </div>
                <table class="adomx-info-table">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Username:</strong></td>
                        <td>{{ $user->username ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><span class="adomx-status-badge adomx-status-{{ $user->role }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td><span class="adomx-status-badge adomx-status-{{ $user->status ?? 'active' }}">{{ ucfirst($user->status ?? 'active') }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Registered:</strong></td>
                        <td>{{ $user->registration_date ? $user->registration_date->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Last Login:</strong></td>
                        <td>{{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Details Tabs -->
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-tabs">
                <button class="adomx-tab-btn active" onclick="showTab('enrollments')">Enrollments</button>
                <button class="adomx-tab-btn" onclick="showTab('quizzes')">Quiz Scores</button>
                <button class="adomx-tab-btn" onclick="showTab('payments')">Payments</button>
                <button class="adomx-tab-btn" onclick="showTab('certificates')">Certificates</button>
                <button class="adomx-tab-btn" onclick="showTab('activity')">Activity Logs</button>
            </div>

            <!-- Enrollments Tab -->
            <div id="enrollments" class="adomx-tab-content active">
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
                            @forelse($enrollmentHistory as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
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

            <!-- Quiz Scores Tab -->
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
                            @forelse($quizScores as $score)
                                <tr>
                                    <td>{{ $score->quiz->title ?? 'N/A' }}</td>
                                    <td>{{ $score->quiz->course->title ?? 'N/A' }}</td>
                                    <td>{{ number_format($score->avg_score, 2) }}%</td>
                                    <td>{{ number_format($score->max_score, 2) }}%</td>
                                    <td>{{ $score->attempts }}</td>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->order_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($payment->total_price, 2) }}</td>
                                    <td><span class="adomx-status-badge adomx-status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No payments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Certificates Tab -->
            <div id="certificates" class="adomx-tab-content">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Issued At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->certificates as $certificate)
                                <tr>
                                    <td>{{ $certificate->course->title ?? 'N/A' }}</td>
                                    <td>{{ $certificate->issued_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('certificates.show', $certificate) }}" class="adomx-action-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No certificates found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Logs Tab -->
            <div id="activity" class="adomx-tab-content">
                <div class="adomx-table-container">
                    <table class="adomx-table">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>Date</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityLogs as $log)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $log->action)) }}</td>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                    <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No activity logs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.adomx-tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.adomx-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
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

