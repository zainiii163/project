<header class="adomx-header">
    <div class="adomx-header-left">
        <button class="adomx-menu-toggle" id="header-menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="adomx-logo-mobile">
            <i class="fas fa-graduation-cap"></i>
            <span>LMS</span>
        </div>
    </div>

    <div class="adomx-header-center">
        <div class="adomx-search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search Here..." class="adomx-search-input">
        </div>
    </div>

    <div class="adomx-header-right">
        <div class="adomx-header-actions">
            <!-- Language -->
            <div class="adomx-header-item adomx-language-dropdown">
                <button class="adomx-header-icon" id="language-dropdown-toggle" title="Language">
                    <i class="fas fa-flag"></i>
                    <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 2px;"></i>
                </button>
                <div class="adomx-dropdown-menu" id="language-dropdown-menu" style="min-width: 200px;">
                    @php
                        $languages = [
                            'en' => 'English',
                            'es' => 'Español',
                            'fr' => 'Français',
                            'de' => 'Deutsch',
                            'ar' => 'العربية',
                            'zh' => '中文',
                            'ja' => '日本語',
                            'pt' => 'Português',
                            'ru' => 'Русский',
                            'hi' => 'हिन्दी'
                        ];
                        $currentLocale = app()->getLocale();
                    @endphp
                    @foreach($languages as $code => $name)
                        <a href="{{ route('locale.switch', $code) }}" class="adomx-dropdown-item {{ $currentLocale === $code ? 'active' : '' }}">
                            <i class="fas fa-{{ $currentLocale === $code ? 'check' : 'circle' }}" style="margin-right: 10px; opacity: {{ $currentLocale === $code ? '1' : '0' }};"></i>
                            <span>{{ $name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Messages -->
            <div class="adomx-header-item">
                <button class="adomx-header-icon adomx-notification-icon" title="Messages">
                    <i class="fas fa-envelope"></i>
                    <span class="adomx-notification-badge">3</span>
                </button>
            </div>

            <!-- Notifications -->
            <div class="adomx-header-item">
                <button class="adomx-header-icon adomx-notification-icon" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="adomx-notification-badge">5</span>
                </button>
            </div>

            <!-- User Profile -->
            <div class="adomx-header-item adomx-user-dropdown">
                <button class="adomx-user-profile" id="user-dropdown-toggle">
                    <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4f46e5&color=fff' }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="adomx-user-avatar">
                    <span class="adomx-user-name">{{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="adomx-dropdown-menu" id="user-dropdown-menu">
                    <a href="#" class="adomx-dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="#" class="adomx-dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <div class="adomx-dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="adomx-dropdown-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
