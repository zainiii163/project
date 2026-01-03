<aside class="adomx-sidebar" id="adomx-sidebar">
    <div class="adomx-sidebar-header">
        <a href="{{ route('teacher.dashboard') }}" class="adomx-logo">
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
            <li class="adomx-nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <a href="{{ route('teacher.dashboard') }}" class="adomx-nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- My Courses -->
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

            <!-- My Lessons -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.lessons.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-video"></i>
                    <span>My Lessons</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('teacher.lessons.index') }}" class="{{ request()->routeIs('teacher.lessons.index') ? 'active' : '' }}">All Lessons</a></li>
                    <li><a href="{{ route('teacher.lessons.create') }}" class="{{ request()->routeIs('teacher.lessons.create') ? 'active' : '' }}">Create Lesson</a></li>
                </ul>
            </li>

            <!-- My Quizzes -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.quizzes.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-question-circle"></i>
                    <span>My Quizzes</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('teacher.quizzes.index') }}" class="{{ request()->routeIs('teacher.quizzes.index') ? 'active' : '' }}">All Quizzes</a></li>
                    <li><a href="{{ route('teacher.quizzes.show', ['quiz' => 1]) }}" class="{{ request()->routeIs('teacher.quizzes.show') ? 'active' : '' }}" style="display: none;">Quiz Details</a></li>
                    <li><small style="padding: 5px 15px; color: var(--text-secondary); font-size: 11px;">Create quiz from course page</small></li>
                </ul>
            </li>

            <!-- My Assignments -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.assignments.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-tasks"></i>
                    <span>My Assignments</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('teacher.assignments.index') }}" class="{{ request()->routeIs('teacher.assignments.index') ? 'active' : '' }}">All Assignments</a></li>
                    <li><a href="{{ route('teacher.assignments.show', ['assignment' => 1]) }}" class="{{ request()->routeIs('teacher.assignments.show') ? 'active' : '' }}" style="display: none;">Assignment Details</a></li>
                    <li><small style="padding: 5px 15px; color: var(--text-secondary); font-size: 11px;">Create assignment from course page</small></li>
                </ul>
            </li>

            <!-- Q&A & Discussions -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.discussions.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.discussions.index') }}" class="adomx-nav-link">
                    <i class="fas fa-comments"></i>
                    <span>Q&A & Discussions</span>
                </a>
            </li>

            <!-- Browse Courses -->
            <li class="adomx-nav-item {{ request()->routeIs('courses.*') && !request()->routeIs('teacher.courses.*') ? 'active' : '' }}">
                <a href="{{ route('courses.index') }}" class="adomx-nav-link">
                    <i class="fas fa-book-open"></i>
                    <span>Browse Courses</span>
                </a>
            </li>

            <!-- Blog -->
            <li class="adomx-nav-item {{ request()->routeIs('blog.*') || request()->routeIs('teacher.blog.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-newspaper"></i>
                    <span>Blog</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('teacher.blog.index') }}" class="{{ request()->routeIs('teacher.blog.index') ? 'active' : '' }}">My Posts</a></li>
                    <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.index') && !request()->routeIs('teacher.blog.*') ? 'active' : '' }}">All Posts</a></li>
                    <li><a href="{{ route('blog.create') }}" class="{{ request()->routeIs('blog.create') ? 'active' : '' }}">Create Post</a></li>
                </ul>
            </li>

            <!-- Course Reviews -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.reviews.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.reviews.index') }}" class="adomx-nav-link">
                    <i class="fas fa-star"></i>
                    <span>Course Reviews</span>
                </a>
            </li>

            <!-- Announcements -->
            <li class="adomx-nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <a href="{{ route('announcements.index') }}" class="adomx-nav-link">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </li>

            <!-- Live Sessions -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.live-sessions.*') || request()->routeIs('live-sessions.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-video"></i>
                    <span>Live Sessions</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('teacher.live-sessions.index') }}" class="{{ request()->routeIs('teacher.live-sessions.index') ? 'active' : '' }}">All Sessions</a></li>
                    <li><a href="{{ route('teacher.live-sessions.create') }}" class="{{ request()->routeIs('teacher.live-sessions.create') ? 'active' : '' }}">Create Session</a></li>
                </ul>
            </li>

            <!-- Calendar -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.calendar.*') || request()->routeIs('calendar.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.calendar.index') }}" class="adomx-nav-link">
                    <i class="fas fa-calendar"></i>
                    <span>Calendar</span>
                </a>
            </li>

            <!-- Payments & Earnings -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.payments.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Payments & Earnings</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('teacher.payments.index') }}" class="{{ request()->routeIs('teacher.payments.index') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('teacher.payments.commissions') }}" class="{{ request()->routeIs('teacher.payments.commissions') ? 'active' : '' }}">Commissions</a></li>
                    <li><a href="{{ route('teacher.payments.payouts') }}" class="{{ request()->routeIs('teacher.payments.payouts') ? 'active' : '' }}">Payouts</a></li>
                </ul>
            </li>

            <!-- Profile -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.profile.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('teacher.profile.show') }}" class="{{ request()->routeIs('teacher.profile.show') ? 'active' : '' }}">View Profile</a></li>
                    <li><a href="{{ route('teacher.profile.edit') }}" class="{{ request()->routeIs('teacher.profile.edit') ? 'active' : '' }}">Edit Profile</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="adomx-sidebar-footer">
        <div style="padding: 15px; border-top: 1px solid var(--border-color);">
            <a href="{{ route('teacher.profile.show') }}" style="text-decoration: none; color: inherit;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px; cursor: pointer;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 500; font-size: 14px;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="adomx-btn adomx-btn-secondary" style="width: 100%; justify-content: center;">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>

