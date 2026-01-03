@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Student Dashboard</h2>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Enrolled Courses</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">{{ $stats['enrolled_courses'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(79, 70, 229, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book" style="font-size: 24px; color: var(--primary-color);"></i>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Completed Courses</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--success-color);">{{ $stats['completed_courses'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="font-size: 24px; color: var(--success-color);"></i>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Certificates</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--info-color);">{{ $stats['certificates'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-certificate" style="font-size: 24px; color: var(--info-color);"></i>
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
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">In Progress</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--warning-color);">{{ $stats['in_progress'] }}</div>
                    </div>
                    <div style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-spinner" style="font-size: 24px; color: var(--warning-color);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>My Enrolled Courses</h3>
                <a href="{{ route('student.courses.index') }}" class="adomx-btn adomx-btn-primary" style="padding: 8px 15px; font-size: 14px;">
                    View All
                </a>
            </div>
            <div class="adomx-card-body">
                @if($enrolled_courses->count() > 0)
                    <div class="adomx-table-container">
                        <table class="adomx-table">
                            <thead>
                                <tr>
                                    <th>Course Title</th>
                                    <th>Teacher</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrolled_courses as $course)
                                    <tr>
                                        <td><strong>{{ $course->title }}</strong></td>
                                        <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                                        <td>
                                            <div class="adomx-progress-bar">
                                                <div class="adomx-progress-fill" style="width: {{ $course->pivot->progress ?? 0 }}%;"></div>
                                            </div>
                                            <span style="font-size: 12px;">{{ $course->pivot->progress ?? 0 }}%</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <a href="{{ route('student.courses.show', $course) }}" class="adomx-action-btn" title="Continue Learning">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                                <a href="{{ route('student.courses.resume', $course) }}" class="adomx-action-btn" title="Resume" style="color: var(--success-color);">
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-book-open" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;"></i>
                        <h3 style="margin-bottom: 10px;">No Enrolled Courses</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 20px;">Start exploring and enroll in courses to begin your learning journey!</p>
                        <a href="{{ route('courses.index') }}" class="adomx-btn adomx-btn-primary">
                            <i class="fas fa-search"></i> Browse Courses
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Certificates -->
    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Recent Certificates</h3>
                <a href="{{ route('student.certificates.index') }}" class="adomx-btn adomx-btn-secondary" style="padding: 8px 15px; font-size: 14px;">
                    View All
                </a>
            </div>
            <div class="adomx-card-body">
                @if($recent_certificates->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        @foreach($recent_certificates as $certificate)
                            <div style="padding: 15px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                    <i class="fas fa-certificate" style="font-size: 24px; color: var(--warning-color);"></i>
                                    <div style="flex: 1;">
                                        <strong style="font-size: 14px;">{{ $certificate->course->title ?? 'N/A' }}</strong>
                                        <div style="font-size: 12px; color: var(--text-secondary);">
                                            {{ $certificate->issued_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('student.certificates.show', $certificate) }}" class="adomx-btn adomx-btn-primary" style="width: 100%; padding: 8px; font-size: 12px;">
                                    <i class="fas fa-eye"></i> View Certificate
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 20px;">
                        <i class="fas fa-certificate" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                        <p style="color: var(--text-secondary); font-size: 14px;">No certificates yet. Complete courses to earn certificates!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Learning Progress Chart -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Learning Progress (Last 6 Months)</h3>
            </div>
            <div class="adomx-card-body">
                <canvas id="progressChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Study Statistics -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Study Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">{{ $total_lessons_completed }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Lessons Completed</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">{{ $total_quizzes_taken }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Quizzes Taken</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">{{ $stats['completed_courses'] }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Courses Completed</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">{{ $stats['certificates'] }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Certificates Earned</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Announcements -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Recent Announcements</h3>
                <a href="{{ route('announcements.index') }}" class="adomx-btn adomx-btn-secondary" style="padding: 8px 15px; font-size: 14px;">
                    View All
                </a>
            </div>
            <div class="adomx-card-body">
                @if($recent_announcements->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($recent_announcements as $announcement)
                            <div style="padding: 12px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px;">
                                <div style="display: flex; align-items: start; gap: 10px;">
                                    <i class="fas fa-bullhorn" style="font-size: 18px; color: var(--info-color); margin-top: 2px;"></i>
                                    <div style="flex: 1;">
                                        <strong style="font-size: 14px; display: block; margin-bottom: 4px;">{{ $announcement->title }}</strong>
                                        <div style="font-size: 12px; color: var(--text-secondary);">
                                            {{ Str::limit($announcement->content, 60) }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                                            {{ $announcement->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 20px;">
                        <i class="fas fa-bullhorn" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                        <p style="color: var(--text-secondary); font-size: 14px;">No announcements yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Assignments -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Upcoming Assignments</h3>
            </div>
            <div class="adomx-card-body">
                @if($upcoming_assignments->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($upcoming_assignments as $assignment)
                            <div style="padding: 12px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px;">
                                <div style="display: flex; align-items: start; gap: 10px;">
                                    <i class="fas fa-tasks" style="font-size: 18px; color: var(--warning-color); margin-top: 2px;"></i>
                                    <div style="flex: 1;">
                                        <strong style="font-size: 14px; display: block; margin-bottom: 4px;">{{ $assignment->course->title ?? 'N/A' }}</strong>
                                        <div style="font-size: 12px; color: var(--text-secondary);">
                                            Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                                            {{ \Carbon\Carbon::parse($assignment->due_date)->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 20px;">
                        <i class="fas fa-tasks" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                        <p style="color: var(--text-secondary); font-size: 14px;">No upcoming assignments.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions - All Features -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Quick Actions - All Features</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <!-- Courses -->
                    <a href="{{ route('student.courses.index') }}" class="adomx-btn adomx-btn-primary" style="padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-book" style="font-size: 32px;"></i>
                        <span>My Courses</span>
                    </a>
                    <a href="{{ route('courses.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-search" style="font-size: 32px;"></i>
                        <span>Browse Courses</span>
                    </a>
                    <a href="{{ route('student.courses.recommendations') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-lightbulb" style="font-size: 32px;"></i>
                        <span>Recommendations</span>
                    </a>
                    <a href="{{ route('student.courses.learning-path') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-route" style="font-size: 32px;"></i>
                        <span>Learning Path</span>
                    </a>
                    
                    <!-- Learning -->
                    <a href="{{ route('student.progress.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-chart-line" style="font-size: 32px;"></i>
                        <span>My Progress</span>
                    </a>
                    <a href="{{ route('student.progress.dashboard') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-chart-bar" style="font-size: 32px;"></i>
                        <span>Progress Dashboard</span>
                    </a>
                    <a href="{{ route('student.assignments.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-tasks" style="font-size: 32px;"></i>
                        <span>Assignments</span>
                    </a>
                    <a href="{{ route('student.quizzes.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-question-circle" style="font-size: 32px;"></i>
                        <span>Quizzes</span>
                    </a>
                    
                    <!-- Achievements -->
                    <a href="{{ route('student.certificates.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-certificate" style="font-size: 32px;"></i>
                        <span>Certificates</span>
                    </a>
                    <a href="{{ route('student.gamification.leaderboard') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-trophy" style="font-size: 32px;"></i>
                        <span>Leaderboard</span>
                    </a>
                    <a href="{{ route('student.gamification.badges') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-medal" style="font-size: 32px;"></i>
                        <span>Badges</span>
                    </a>
                    <a href="{{ route('student.gamification.my-progress') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-chart-pie" style="font-size: 32px;"></i>
                        <span>My Gamification</span>
                    </a>
                    
                    <!-- Community -->
                    <a href="{{ route('student.courses.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-comments" style="font-size: 32px;"></i>
                        <span>Discussions</span>
                    </a>
                    <a href="{{ route('student.reviews.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-star" style="font-size: 32px;"></i>
                        <span>My Reviews</span>
                    </a>
                    
                    <!-- Features -->
                    <a href="{{ route('student.live-sessions.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-video" style="font-size: 32px;"></i>
                        <span>Live Sessions</span>
                    </a>
                    <a href="{{ route('student.calendar.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-calendar" style="font-size: 32px;"></i>
                        <span>Calendar</span>
                    </a>
                    <a href="{{ route('student.support.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-headset" style="font-size: 32px;"></i>
                        <span>Support</span>
                    </a>
                    <a href="{{ route('student.referrals.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-user-friends" style="font-size: 32px;"></i>
                        <span>Referrals</span>
                    </a>
                    <a href="{{ route('student.notifications.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-bell" style="font-size: 32px;"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="{{ route('announcements.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-bullhorn" style="font-size: 32px;"></i>
                        <span>Announcements</span>
                    </a>
                    
                    <!-- Payments & Subscriptions -->
                    <a href="{{ route('student.payments.history') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-history" style="font-size: 32px;"></i>
                        <span>Payment History</span>
                    </a>
                    <a href="{{ route('student.payments.invoices') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-file-invoice" style="font-size: 32px;"></i>
                        <span>Invoices</span>
                    </a>
                    <a href="{{ route('student.subscriptions.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-sync-alt" style="font-size: 32px;"></i>
                        <span>Subscriptions</span>
                    </a>
                    
                    <!-- Resources -->
                    <a href="{{ route('student.resources.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-folder-open" style="font-size: 32px;"></i>
                        <span>Resources</span>
                    </a>
                    <a href="{{ route('student.offline.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-download" style="font-size: 32px;"></i>
                        <span>Offline Access</span>
                    </a>
                    <a href="{{ route('student.surveys.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-poll" style="font-size: 32px;"></i>
                        <span>Surveys</span>
                    </a>
                    <a href="{{ route('student.feedback.index') }}" class="adomx-btn" style="background: var(--dark-bg-light); color: var(--text-primary); padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <i class="fas fa-comment-dots" style="font-size: 32px;"></i>
                        <span>Feedback</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Learning Progress Chart
    const progressCtx = document.getElementById('progressChart');
    if (progressCtx) {
        const progressData = @json($progressData);
        new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: progressData.map(item => item.month),
                datasets: [{
                    label: 'Lessons Completed',
                    data: progressData.map(item => item.lessons_completed),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection

