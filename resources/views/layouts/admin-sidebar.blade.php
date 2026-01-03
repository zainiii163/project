<aside class="adomx-sidebar" id="adomx-sidebar">
    <div class="adomx-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="adomx-logo">
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
            <li class="adomx-nav-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('teacher.dashboard') || request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="adomx-nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <!-- User Management -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') && !request()->has('role') ? 'active' : '' }}">All Users</a></li>
                    <li><a href="{{ route('admin.teachers.index') }}" class="{{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">Teachers</a></li>
                    <li><a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">Students</a></li>
                    <li><a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="{{ request()->get('role') == 'admin' ? 'active' : '' }}">Admins</a></li>
                    <li><a href="{{ route('admin.users.create') }}" class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">Create User</a></li>
                </ul>
            </li>

            <!-- Category Management -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-folder"></i>
                    <span>Categories</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">All Categories</a></li>
                    <li><a href="{{ route('admin.categories.create') }}" class="{{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">Create Category</a></li>
                </ul>
            </li>

            <!-- Coupons & Discounts -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-tag"></i>
                    <span>Coupons & Discounts</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.index') ? 'active' : '' }}">All Coupons</a></li>
                    <li><a href="{{ route('admin.coupons.create') }}" class="{{ request()->routeIs('admin.coupons.create') ? 'active' : '' }}">Create Coupon</a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <!-- Course Management -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-book"></i>
                    <span>Course Management</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.courses.index') }}" class="{{ request()->routeIs('admin.courses.index') ? 'active' : '' }}">All Courses</a></li>
                    <li><a href="{{ route('admin.courses.create') }}" class="{{ request()->routeIs('admin.courses.create') ? 'active' : '' }}">Create Course</a></li>
                </ul>
            </li>

            <!-- Lesson Management -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.lessons.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-video"></i>
                    <span>Lesson Management</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.lessons.index') }}" class="{{ request()->routeIs('admin.lessons.index') ? 'active' : '' }}">All Lessons</a></li>
                    <li><a href="{{ route('admin.lessons.create') }}" class="{{ request()->routeIs('admin.lessons.create') ? 'active' : '' }}">Create Lesson</a></li>
                </ul>
            </li>

            <!-- Quiz Management -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-question-circle"></i>
                    <span>Quiz Management</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.quizzes.index') }}" class="{{ request()->routeIs('admin.quizzes.index') ? 'active' : '' }}">All Quizzes</a></li>
                    <li><a href="{{ route('admin.quizzes.create') }}" class="{{ request()->routeIs('admin.quizzes.create') ? 'active' : '' }}">Create Quiz</a></li>
                </ul>
            </li>
            @endif

            <!-- Courses (Public) -->
            <li class="adomx-nav-item {{ request()->routeIs('courses.*') && !request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                <a href="{{ route('courses.index') }}" class="adomx-nav-link">
                    <i class="fas fa-book-open"></i>
                    <span>Browse Courses</span>
                </a>
            </li>

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <!-- Orders & Payments -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.orders.*') || request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders & Payments</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a></li>
                    <li><a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.index') ? 'active' : '' }}">All Payments</a></li>
                    <li><a href="{{ route('admin.payments.transactions') }}" class="{{ request()->routeIs('admin.payments.transactions') ? 'active' : '' }}">Transactions</a></li>
                    <li><a href="{{ route('admin.payments.coupons') }}" class="{{ request()->routeIs('admin.payments.coupons') ? 'active' : '' }}">Coupons</a></li>
                    <li><a href="{{ route('admin.payments.revenue-report') }}" class="{{ request()->routeIs('admin.payments.revenue-report') ? 'active' : '' }}">Revenue Report</a></li>
                </ul>
            </li>

            <!-- Subscriptions -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-credit-card"></i>
                    <span>Subscriptions</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.index') ? 'active' : '' }}">All Subscriptions</a></li>
                    <li><a href="{{ route('admin.subscriptions.create') }}" class="{{ request()->routeIs('admin.subscriptions.create') ? 'active' : '' }}">Create Subscription</a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->isTeacher())
            <!-- Teacher Panel -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.courses.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-book"></i>
                    <span>My Courses</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('teacher.courses.index') }}" class="{{ request()->routeIs('teacher.courses.index') ? 'active' : '' }}">All My Courses</a></li>
                    <li><a href="{{ route('teacher.courses.create') }}" class="{{ request()->routeIs('teacher.courses.create') ? 'active' : '' }}">Create Course</a></li>
                </ul>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('teacher.lessons.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.lessons.index') }}" class="adomx-nav-link">
                    <i class="fas fa-video"></i>
                    <span>My Lessons</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('teacher.quizzes.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.quizzes.index') }}" class="adomx-nav-link">
                    <i class="fas fa-question-circle"></i>
                    <span>My Quizzes</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('teacher.assignments.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.assignments.index') }}" class="adomx-nav-link">
                    <i class="fas fa-tasks"></i>
                    <span>My Assignments</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('teacher.discussions.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.discussions.index') }}" class="adomx-nav-link">
                    <i class="fas fa-comments"></i>
                    <span>Q&A & Discussions</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('teacher.payments.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.payments.index') }}" class="adomx-nav-link">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Payments & Earnings</span>
                </a>
            </li>
            @elseif(auth()->user()->isStudent())
            <!-- Student Panel -->
            <li class="adomx-nav-item {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-book"></i>
                    <span>My Courses</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.courses.index') }}" class="{{ request()->routeIs('student.courses.index') ? 'active' : '' }}">All Courses</a></li>
                    <li><a href="{{ route('student.courses.recommendations') }}" class="{{ request()->routeIs('student.courses.recommendations') ? 'active' : '' }}">Recommendations</a></li>
                    <li><a href="{{ route('student.courses.learning-path') }}" class="{{ request()->routeIs('student.courses.learning-path') ? 'active' : '' }}">Learning Path</a></li>
                </ul>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.community.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-users"></i>
                    <span>Community</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('student.community.discussions') ? 'active' : '' }}">Discussions</a></li>
                    <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('student.community.qa') ? 'active' : '' }}">Q&A</a></li>
                </ul>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.payments.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('student.payments.history') }}" class="{{ request()->routeIs('student.payments.history') ? 'active' : '' }}">Transaction History</a></li>
                    <li><a href="{{ route('student.payments.invoices') }}" class="{{ request()->routeIs('student.payments.invoices') ? 'active' : '' }}">Invoices</a></li>
                    <li><a href="{{ route('student.payments.subscriptions') }}" class="{{ request()->routeIs('student.payments.subscriptions') ? 'active' : '' }}">Subscriptions</a></li>
                </ul>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.progress.*') ? 'active' : '' }}">
                <a href="{{ route('student.progress.index') }}" class="adomx-nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span>My Progress</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.assignments.*') ? 'active' : '' }}">
                <a href="{{ route('student.assignments.index') }}" class="adomx-nav-link">
                    <i class="fas fa-tasks"></i>
                    <span>My Assignments</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.quizzes.*') ? 'active' : '' }}">
                <a href="{{ route('student.quizzes.index') }}" class="adomx-nav-link">
                    <i class="fas fa-question-circle"></i>
                    <span>My Quizzes</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.certificates.*') ? 'active' : '' }}">
                <a href="{{ route('student.certificates.index') }}" class="adomx-nav-link">
                    <i class="fas fa-certificate"></i>
                    <span>My Certificates</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.reviews.*') ? 'active' : '' }}">
                <a href="{{ route('student.reviews.index') }}" class="adomx-nav-link">
                    <i class="fas fa-star"></i>
                    <span>My Reviews</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.support.*') || request()->routeIs('support.*') ? 'active' : '' }}">
                <a href="{{ route('support.index') }}" class="adomx-nav-link">
                    <i class="fas fa-headset"></i>
                    <span>Support & Help</span>
                </a>
            </li>

            <li class="adomx-nav-item {{ request()->routeIs('student.referrals.*') || request()->routeIs('referrals.*') ? 'active' : '' }}">
                <a href="{{ route('referrals.index') }}" class="adomx-nav-link">
                    <i class="fas fa-user-friends"></i>
                    <span>Referrals</span>
                </a>
            </li>
            @else
            <!-- Assignments (for other roles) -->
            <li class="adomx-nav-item {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-tasks"></i>
                    <span>Assignments</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.index') ? 'active' : '' }}">View by Course</a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <!-- Reviews & Ratings -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reviews.index') }}" class="adomx-nav-link">
                    <i class="fas fa-star"></i>
                    <span>Reviews & Ratings</span>
                </a>
            </li>

            <!-- Discussions & Q&A -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.discussions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.discussions.index') }}" class="adomx-nav-link">
                    <i class="fas fa-comments"></i>
                    <span>Discussions & Q&A</span>
                </a>
            </li>
            @else
            <!-- Discussions -->
            <li class="adomx-nav-item {{ request()->routeIs('discussions.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-comments"></i>
                    <span>Discussions</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.index') ? 'active' : '' }}">View by Course</a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <!-- Blog Management -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-newspaper"></i>
                    <span>Blog Management</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.blog.index') }}" class="{{ request()->routeIs('admin.blog.index') ? 'active' : '' }}">All Posts</a></li>
                    <li><a href="{{ route('admin.blog.create') }}" class="{{ request()->routeIs('admin.blog.create') ? 'active' : '' }}">Create Post</a></li>
                </ul>
            </li>
            @else
            <!-- Blog -->
            <li class="adomx-nav-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-newspaper"></i>
                    <span>Blog</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.index') ? 'active' : '' }}">All Posts</a></li>
                    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <li><a href="{{ route('blog.create') }}" class="{{ request()->routeIs('blog.create') ? 'active' : '' }}">Create Post</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <!-- Announcements -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.index') ? 'active' : '' }}">All Announcements</a></li>
                    <li><a href="{{ route('admin.announcements.create') }}" class="{{ request()->routeIs('admin.announcements.create') ? 'active' : '' }}">Create Announcement</a></li>
                </ul>
            </li>

            <!-- Notifications -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">All Notifications</a></li>
                    <li><a href="{{ route('admin.notifications.create') }}" class="{{ request()->routeIs('admin.notifications.create') ? 'active' : '' }}">Send Notification</a></li>
                </ul>
            </li>

            <!-- Certificates -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-certificate"></i>
                    <span>Certificates</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.certificates.index') }}" class="{{ request()->routeIs('admin.certificates.index') ? 'active' : '' }}">All Certificates</a></li>
                    <li><a href="{{ route('admin.certificates.create') }}" class="{{ request()->routeIs('admin.certificates.create') ? 'active' : '' }}">Create Certificate</a></li>
                </ul>
            </li>
            @else
            <!-- Announcements -->
            <li class="adomx-nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <a href="{{ route('announcements.index') }}" class="adomx-nav-link">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </li>

            <!-- Certificates -->
            <li class="adomx-nav-item {{ request()->routeIs('certificates.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-certificate"></i>
                    <span>Certificates</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.index') ? 'active' : '' }}">View by Course</a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <!-- Analytics & Reporting -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics & Reporting</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.index') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.analytics.kpis') }}" class="{{ request()->routeIs('admin.analytics.kpis') ? 'active' : '' }}">KPIs</a></li>
                    <li><a href="{{ route('admin.analytics.courses') }}" class="{{ request()->routeIs('admin.analytics.courses') ? 'active' : '' }}">Course Analytics</a></li>
                    <li><a href="{{ route('admin.analytics.revenue') }}" class="{{ request()->routeIs('admin.analytics.revenue') ? 'active' : '' }}">Revenue</a></li>
                    <li><a href="{{ route('admin.analytics.users') }}" class="{{ request()->routeIs('admin.analytics.users') ? 'active' : '' }}">User Analytics</a></li>
                    <li><a href="{{ route('admin.analytics.quiz-stats') }}" class="{{ request()->routeIs('admin.analytics.quiz-stats') ? 'active' : '' }}">Quiz Statistics</a></li>
                    <li><a href="{{ route('admin.analytics.ai-insights') }}" class="{{ request()->routeIs('admin.analytics.ai-insights') ? 'active' : '' }}">AI Insights</a></li>
                </ul>
            </li>

            <!-- Settings -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">General</a></li>
                    <li><a href="{{ route('admin.settings.branding') }}" class="{{ request()->routeIs('admin.settings.branding') ? 'active' : '' }}">Branding</a></li>
                    <li><a href="{{ route('admin.settings.email-templates') }}" class="{{ request()->routeIs('admin.settings.email-templates') ? 'active' : '' }}">Email Templates</a></li>
                    <li><a href="{{ route('admin.settings.notifications') }}" class="{{ request()->routeIs('admin.settings.notifications') ? 'active' : '' }}">Notifications</a></li>
                    <li><a href="{{ route('admin.settings.seo') }}" class="{{ request()->routeIs('admin.settings.seo') ? 'active' : '' }}">SEO</a></li>
                    <li><a href="{{ route('admin.settings.localization') }}" class="{{ request()->routeIs('admin.settings.localization') ? 'active' : '' }}">Localization</a></li>
                    <li><a href="{{ route('admin.settings.storage') }}" class="{{ request()->routeIs('admin.settings.storage') ? 'active' : '' }}">Storage</a></li>
                    <li><a href="{{ route('admin.settings.gamification') }}" class="{{ request()->routeIs('admin.settings.gamification') ? 'active' : '' }}">Gamification</a></li>
                    <li><a href="{{ route('admin.settings.integrations') }}" class="{{ request()->routeIs('admin.settings.integrations') ? 'active' : '' }}">Integrations</a></li>
                    <li><a href="{{ route('admin.settings.security') }}" class="{{ request()->routeIs('admin.settings.security') ? 'active' : '' }}">Security</a></li>
                    <li><a href="{{ route('admin.settings.backup') }}" class="{{ request()->routeIs('admin.settings.backup') ? 'active' : '' }}">Backup</a></li>
                </ul>
            </li>

            <!-- Gamification -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.gamification.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-trophy"></i>
                    <span>Gamification</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.gamification.index') }}" class="{{ request()->routeIs('admin.gamification.index') ? 'active' : '' }}">Badges</a></li>
                    <li><a href="{{ route('admin.gamification.create') }}" class="{{ request()->routeIs('admin.gamification.create') ? 'active' : '' }}">Create Badge</a></li>
                    <li><a href="{{ route('admin.gamification.leaderboard') }}" class="{{ request()->routeIs('admin.gamification.leaderboard') ? 'active' : '' }}">Leaderboard</a></li>
                </ul>
            </li>

            <!-- Live Sessions -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.live-sessions.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-video"></i>
                    <span>Live Sessions</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.live-sessions.index') }}" class="{{ request()->routeIs('admin.live-sessions.index') ? 'active' : '' }}">All Sessions</a></li>
                    <li><a href="{{ route('admin.live-sessions.create') }}" class="{{ request()->routeIs('admin.live-sessions.create') ? 'active' : '' }}">Create Session</a></li>
                </ul>
            </li>

            <!-- Calendar -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.calendar.*') || request()->routeIs('calendar.*') ? 'active' : '' }}">
                <a href="{{ route('admin.calendar.index') }}" class="adomx-nav-link">
                    <i class="fas fa-calendar"></i>
                    <span>Calendar</span>
                </a>
            </li>

            <!-- Support & Helpdesk -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-headset"></i>
                    <span>Support & Helpdesk</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.support.index') }}" class="{{ request()->routeIs('admin.support.index') ? 'active' : '' }}">All Tickets</a></li>
                    <li><a href="{{ route('admin.support.analytics') }}" class="{{ request()->routeIs('admin.support.analytics') ? 'active' : '' }}">Analytics</a></li>
                </ul>
            </li>

            <!-- Reporting -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-file-alt"></i>
                    <span>Reports</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">All Reports</a></li>
                    <li><a href="{{ route('admin.reports.enrollments') }}" class="{{ request()->routeIs('admin.reports.enrollments') ? 'active' : '' }}">Enrollments</a></li>
                    <li><a href="{{ route('admin.reports.revenue') }}" class="{{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}">Revenue</a></li>
                    <li><a href="{{ route('admin.reports.users') }}" class="{{ request()->routeIs('admin.reports.users') ? 'active' : '' }}">Users</a></li>
                    <li><a href="{{ route('admin.reports.courses') }}" class="{{ request()->routeIs('admin.reports.courses') ? 'active' : '' }}">Courses</a></li>
                </ul>
            </li>

            <!-- SEO & Marketing -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
                <a href="{{ route('admin.seo.index') }}" class="adomx-nav-link">
                    <i class="fas fa-search"></i>
                    <span>SEO & Marketing</span>
                </a>
            </li>

            <!-- Payouts & Commissions -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.payouts.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Payouts & Commissions</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.payouts.index') }}" class="{{ request()->routeIs('admin.payouts.index') ? 'active' : '' }}">All Payouts</a></li>
                    <li><a href="{{ route('admin.payouts.create') }}" class="{{ request()->routeIs('admin.payouts.create') ? 'active' : '' }}">Create Payout</a></li>
                </ul>
            </li>

            <!-- Membership Plans -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.membership-plans.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-crown"></i>
                    <span>Membership Plans</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.membership-plans.index') }}" class="{{ request()->routeIs('admin.membership-plans.index') ? 'active' : '' }}">All Plans</a></li>
                    <li><a href="{{ route('admin.membership-plans.create') }}" class="{{ request()->routeIs('admin.membership-plans.create') ? 'active' : '' }}">Create Plan</a></li>
                </ul>
            </li>

            <!-- Content Moderation -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}">
                <a href="{{ route('admin.moderation.index') }}" class="adomx-nav-link">
                    <i class="fas fa-shield-alt"></i>
                    <span>Content Moderation</span>
                </a>
            </li>

            <!-- Surveys -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.surveys.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-poll"></i>
                    <span>Surveys</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('admin.surveys.index') }}" class="{{ request()->routeIs('admin.surveys.index') ? 'active' : '' }}">All Surveys</a></li>
                    <li><a href="{{ route('admin.surveys.create') }}" class="{{ request()->routeIs('admin.surveys.create') ? 'active' : '' }}">Create Survey</a></li>
                </ul>
            </li>

            <!-- Feedback -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                <a href="{{ route('admin.feedback.index') }}" class="adomx-nav-link">
                    <i class="fas fa-comment-dots"></i>
                    <span>Feedback</span>
                </a>
            </li>

            <!-- Resource Library -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
                <a href="{{ route('admin.resources.index') }}" class="adomx-nav-link">
                    <i class="fas fa-folder-open"></i>
                    <span>Resource Library</span>
                </a>
            </li>

            <!-- Audit Logs -->
            <li class="adomx-nav-item {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                <a href="{{ route('admin.audit-logs.index') }}" class="adomx-nav-link">
                    <i class="fas fa-history"></i>
                    <span>Audit Logs</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
</aside>
