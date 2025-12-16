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
                <a href="{{ route('teacher.lessons.index') }}" class="adomx-nav-link">
                    <i class="fas fa-video"></i>
                    <span>My Lessons</span>
                </a>
            </li>

            <!-- My Quizzes -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.quizzes.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.quizzes.index') }}" class="adomx-nav-link">
                    <i class="fas fa-question-circle"></i>
                    <span>My Quizzes</span>
                </a>
            </li>

            <!-- My Assignments -->
            <li class="adomx-nav-item {{ request()->routeIs('teacher.assignments.*') ? 'active' : '' }}">
                <a href="{{ route('teacher.assignments.index') }}" class="adomx-nav-link">
                    <i class="fas fa-tasks"></i>
                    <span>My Assignments</span>
                </a>
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
            <li class="adomx-nav-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="adomx-nav-link adomx-nav-toggle">
                    <i class="fas fa-newspaper"></i>
                    <span>Blog</span>
                    <i class="fas fa-chevron-down adomx-nav-arrow"></i>
                </a>
                <ul class="adomx-nav-submenu">
                    <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.index') ? 'active' : '' }}">All Posts</a></li>
                    <li><a href="{{ route('blog.create') }}" class="{{ request()->routeIs('blog.create') ? 'active' : '' }}">Create Post</a></li>
                </ul>
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

    <!-- Sidebar Footer -->
    <div class="adomx-sidebar-footer">
        <div style="padding: 15px; border-top: 1px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 14px;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">{{ auth()->user()->email }}</div>
                </div>
            </div>
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

