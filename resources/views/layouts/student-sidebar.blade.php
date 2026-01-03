<aside class="adomx-sidebar" id="adomx-sidebar">
    <div class="adomx-sidebar-header">
        <a href="{{ route('student.dashboard') }}" class="adomx-logo">
            <i class="fas fa-graduation-cap"></i>
            <span>LMS</span>
        </a>
        <button class="adomx-sidebar-toggle" id="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <nav class="adomx-sidebar-nav">
        <ul class="adomx-nav-menu">
            <!-- Dashboard -->
            <li class="adomx-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}" class="adomx-nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- My Courses -->
            <li class="adomx-nav-item {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-book"></i>
                    <span>My Courses</span>
                    @if(request()->routeIs('student.courses.*'))
                        <i class="fas fa-chevron-up adomx-nav-arrow"></i>
                    @else
                        <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                    @endif
                </a>
                <ul class="adomx-nav-submenu" style="{{ request()->routeIs('student.courses.*') ? 'max-height: 500px;' : '' }}">
                    <li><a href="{{ route('student.courses.index') }}" class="{{ request()->routeIs('student.courses.index') ? 'active' : '' }}">All Courses</a></li>
                    <li><a href="{{ route('student.courses.recommendations') }}" class="{{ request()->routeIs('student.courses.recommendations') ? 'active' : '' }}">Recommendations</a></li>
                    <li><a href="{{ route('student.courses.learning-path') }}" class="{{ request()->routeIs('student.courses.learning-path') ? 'active' : '' }}">Learning Path</a></li>
                </ul>
            </li>

            <!-- My Progress -->
            <li class="adomx-nav-item {{ request()->routeIs('student.progress.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-chart-line"></i>
                    <span>My Progress</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.progress.index') }}" class="{{ request()->routeIs('student.progress.index') ? 'active' : '' }}">Progress Overview</a></li>
                    <li><a href="{{ route('student.progress.dashboard') }}" class="{{ request()->routeIs('student.progress.dashboard') ? 'active' : '' }}">Analytics Dashboard</a></li>
                </ul>
            </li>

            <!-- My Assignments -->
            <li class="adomx-nav-item {{ request()->routeIs('student.assignments.*') ? 'active' : '' }}">
                <a href="{{ route('student.assignments.index') }}" class="adomx-nav-link">
                    <i class="fas fa-tasks"></i>
                    <span>My Assignments</span>
                </a>
            </li>

            <!-- My Quizzes -->
            <li class="adomx-nav-item {{ request()->routeIs('student.quizzes.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-question-circle"></i>
                    <span>My Quizzes</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.quizzes.index') }}" class="{{ request()->routeIs('student.quizzes.index') ? 'active' : '' }}">All Quizzes</a></li>
                    <li><a href="{{ route('student.quizzes.attempts') }}" class="{{ request()->routeIs('student.quizzes.attempts') ? 'active' : '' }}">My Attempts</a></li>
                    <li><a href="{{ route('student.quizzes.improvement') }}" class="{{ request()->routeIs('student.quizzes.improvement') ? 'active' : '' }}">Improvement Tracking</a></li>
                </ul>
            </li>

            <!-- My Certificates -->
            <li class="adomx-nav-item {{ request()->routeIs('student.certificates.*') ? 'active' : '' }}">
                <a href="{{ route('student.certificates.index') }}" class="adomx-nav-link">
                    <i class="fas fa-certificate"></i>
                    <span>My Certificates</span>
                </a>
            </li>

            <!-- My Reviews -->
            <li class="adomx-nav-item {{ request()->routeIs('student.reviews.*') ? 'active' : '' }}">
                <a href="{{ route('student.reviews.index') }}" class="adomx-nav-link">
                    <i class="fas fa-star"></i>
                    <span>My Reviews</span>
                </a>
            </li>

            <!-- Community -->
            <li class="adomx-nav-item {{ request()->routeIs('student.community.*') || request()->routeIs('discussions.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-users"></i>
                    <span>Community</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('student.community.discussions') || request()->routeIs('student.community.qa') || request()->routeIs('discussions.*') ? 'active' : '' }}">Browse Courses</a></li>
                    <li><small style="padding: 5px 15px; color: var(--text-secondary); font-size: 11px;">Discussions & Q&A are accessed from course pages</small></li>
                </ul>
            </li>

            <!-- Payments -->
            <li class="adomx-nav-item {{ request()->routeIs('student.payments.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.payments.history') }}" class="{{ request()->routeIs('student.payments.history') ? 'active' : '' }}">Transaction History</a></li>
                    <li><a href="{{ route('student.payments.invoices') }}" class="{{ request()->routeIs('student.payments.invoices') || request()->routeIs('student.payments.invoices.download') ? 'active' : '' }}">Invoices</a></li>
                </ul>
            </li>

            <!-- Subscriptions -->
            <li class="adomx-nav-item {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                <a href="{{ route('subscriptions.index') }}" class="adomx-nav-link">
                    <i class="fas fa-crown"></i>
                    <span>Subscriptions</span>
                </a>
            </li>

            <!-- Browse Courses -->
            <li class="adomx-nav-item {{ request()->routeIs('courses.*') && !request()->routeIs('student.courses.*') ? 'active' : '' }}">
                <a href="{{ route('courses.index') }}" class="adomx-nav-link">
                    <i class="fas fa-book-open"></i>
                    <span>Browse Courses</span>
                </a>
            </li>

            <!-- Blog -->
            <li class="adomx-nav-item {{ request()->routeIs('blog.*') || request()->routeIs('student.blog.*') ? 'active' : '' }}">
                <a href="{{ route('student.blog.index') }}" class="adomx-nav-link">
                    <i class="fas fa-newspaper"></i>
                    <span>Blog</span>
                </a>
            </li>

            <!-- Gamification -->
            <li class="adomx-nav-item {{ request()->routeIs('student.gamification.*') || request()->routeIs('gamification.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-trophy"></i>
                    <span>Gamification</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.gamification.leaderboard') }}" class="{{ request()->routeIs('student.gamification.leaderboard') ? 'active' : '' }}">Leaderboard</a></li>
                    <li><a href="{{ route('student.gamification.badges') }}" class="{{ request()->routeIs('student.gamification.badges') ? 'active' : '' }}">My Badges</a></li>
                    <li><a href="{{ route('student.gamification.my-progress') }}" class="{{ request()->routeIs('student.gamification.my-progress') ? 'active' : '' }}">My Progress</a></li>
                </ul>
            </li>

            <!-- Live Sessions -->
            <li class="adomx-nav-item {{ request()->routeIs('student.live-sessions.*') || request()->routeIs('live-sessions.*') ? 'active' : '' }}">
                <a href="{{ route('student.live-sessions.index') }}" class="adomx-nav-link">
                    <i class="fas fa-video"></i>
                    <span>Live Sessions</span>
                </a>
            </li>

            <!-- Calendar -->
            <li class="adomx-nav-item {{ request()->routeIs('student.calendar.*') || request()->routeIs('calendar.*') ? 'active' : '' }}">
                <a href="{{ route('student.calendar.index') }}" class="adomx-nav-link">
                    <i class="fas fa-calendar"></i>
                    <span>Calendar</span>
                </a>
            </li>

            <!-- Support & Helpdesk -->
            <li class="adomx-nav-item {{ request()->routeIs('student.support.*') || request()->routeIs('support.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-headset"></i>
                    <span>Support</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.support.index') }}" class="{{ request()->routeIs('student.support.index') ? 'active' : '' }}">My Tickets</a></li>
                    <li><a href="{{ route('student.support.create') }}" class="{{ request()->routeIs('student.support.create') ? 'active' : '' }}">Create Ticket</a></li>
                </ul>
            </li>

            <!-- Referrals -->
            <li class="adomx-nav-item {{ request()->routeIs('student.referrals.*') || request()->routeIs('referrals.*') ? 'active' : '' }}">
                <a href="{{ route('student.referrals.index') }}" class="adomx-nav-link">
                    <i class="fas fa-user-friends"></i>
                    <span>Referrals</span>
                </a>
            </li>

            <!-- Notifications -->
            <li class="adomx-nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <a href="{{ route('notifications.index') }}" class="adomx-nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>

            <!-- Announcements -->
            <li class="adomx-nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <a href="{{ route('announcements.index') }}" class="adomx-nav-link">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </li>

            <!-- Offline Access -->
            <li class="adomx-nav-item {{ request()->routeIs('student.offline.*') || request()->routeIs('offline.*') ? 'active' : '' }}">
                <a href="{{ route('student.offline.index') }}" class="adomx-nav-link">
                    <i class="fas fa-download"></i>
                    <span>Offline Access</span>
                </a>
            </li>

            <!-- Surveys -->
            <li class="adomx-nav-item {{ request()->routeIs('student.surveys.*') || request()->routeIs('surveys.*') ? 'active' : '' }}">
                <a href="{{ route('student.surveys.index') }}" class="adomx-nav-link">
                    <i class="fas fa-poll"></i>
                    <span>Surveys</span>
                </a>
            </li>

            <!-- Feedback -->
            <li class="adomx-nav-item {{ request()->routeIs('student.feedback.*') || request()->routeIs('feedback.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-comment-dots"></i>
                    <span>Feedback</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.feedback.index') }}" class="{{ request()->routeIs('student.feedback.index') ? 'active' : '' }}">My Feedback</a></li>
                    <li><a href="{{ route('student.feedback.create') }}" class="{{ request()->routeIs('student.feedback.create') ? 'active' : '' }}">Submit Feedback</a></li>
                </ul>
            </li>

            <!-- Resources -->
            <li class="adomx-nav-item {{ request()->routeIs('student.resources.*') || request()->routeIs('resources.*') ? 'active' : '' }}">
                <a href="{{ route('student.resources.index') }}" class="adomx-nav-link">
                    <i class="fas fa-folder-open"></i>
                    <span>Resource Library</span>
                </a>
            </li>

            <!-- Profile -->
            <li class="adomx-nav-item {{ request()->routeIs('student.profile.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.profile.show') }}" class="{{ request()->routeIs('student.profile.show') ? 'active' : '' }}">View Profile</a></li>
                    <li><a href="{{ route('student.profile.edit') }}" class="{{ request()->routeIs('student.profile.edit') ? 'active' : '' }}">Edit Profile</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>

