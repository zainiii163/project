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
                <a href="{{ route('student.progress.index') }}" class="adomx-nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span>My Progress</span>
                </a>
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
                    <li><a href="{{ route('student.payments.subscriptions') }}" class="{{ request()->routeIs('student.payments.subscriptions') || request()->routeIs('student.payments.subscriptions.purchase') ? 'active' : '' }}">Subscriptions</a></li>
                </ul>
            </li>

            <!-- Browse Courses -->
            <li class="adomx-nav-item {{ request()->routeIs('courses.*') && !request()->routeIs('student.courses.*') ? 'active' : '' }}">
                <a href="{{ route('courses.index') }}" class="adomx-nav-link">
                    <i class="fas fa-book-open"></i>
                    <span>Browse Courses</span>
                </a>
            </li>

            <!-- Blog -->
            <li class="adomx-nav-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                <a href="{{ route('blog.index') }}" class="adomx-nav-link">
                    <i class="fas fa-newspaper"></i>
                    <span>Blog</span>
                </a>
            </li>

            <!-- Announcements -->
            <li class="adomx-nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <a href="{{ route('announcements.index') }}" class="adomx-nav-link">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

