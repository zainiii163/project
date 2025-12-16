<!DOCTYPE html>
<html lang="en" class="dark-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Learning Management System</title>
    <meta name="description" content="Professional Learning Management System">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/adomx.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="adomx-wrapper">
        <!-- Sidebar -->
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            @include('layouts.admin-sidebar')
        @elseif(auth()->user()->isTeacher())
            @include('layouts.teacher-sidebar')
        @elseif(auth()->user()->isStudent())
            @include('layouts.student-sidebar')
        @else
            @include('layouts.admin-sidebar')
        @endif
        
        <!-- Main Content -->
        <div class="adomx-main-content">
            <!-- Header -->
            @include('layouts.admin-navbar')
            
            <!-- Content Area -->
            <div class="adomx-content-area">
                @if(session('success'))
                    <div class="adomx-alert adomx-alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                        <button type="button" class="adomx-alert-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="adomx-alert adomx-alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                        <button type="button" class="adomx-alert-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="adomx-alert adomx-alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="adomx-alert-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                @endif

                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="adomx-footer">
                <p>&copy; {{ date('Y') }} LMS - Learning Management System. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/admin/adomx.js') }}"></script>
    @stack('scripts')
</body>
</html>
