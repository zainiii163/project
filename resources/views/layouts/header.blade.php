<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Consbus - Digital Agency</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Consbus - World Leading Digital Agency">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- CSS -->
    
    <!-- Google Fonts CSS -->  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flaticon.css') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <!-- Consbus Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/consbus.css') }}">

</head>

<body>

    <div class="main-wrapper">

        <!-- Header Section Start -->
        <header class="consbus-header-section">
            <div class="container">
                <div class="consbus-header-wrapper">
                    <!-- Logo Start -->
                    <div class="consbus-logo">
                        <a href="{{ route('home') }}">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="20" cy="20" r="18" stroke="white" stroke-width="2" fill="none"/>
                                <path d="M12 20 Q20 12, 28 20 Q20 28, 12 20" stroke="white" stroke-width="2" fill="none"/>
                                <circle cx="20" cy="20" r="8" stroke="white" stroke-width="1.5" fill="none"/>
                            </svg>
                            <span class="consbus-logo-text">Consbus</span>
                        </a>
                    </div>
                    <!-- Logo End -->

                    <!-- Navigation Menu Start -->
                    <nav class="consbus-nav d-none d-lg-block">
                        <ul class="consbus-nav-menu">
                            <li><a href="{{ route('home') }}" class="active">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#service">Service</a></li>
                            <li><a href="#case-study">Case Study</a></li>
                            <li><a href="#team">Team</a></li>
                        <li><a href="#blog">Blog</a></li>
                    </ul>
                </nav>
                <!-- Navigation Menu End -->

                <!-- Header Buttons Start -->
                    <div class="consbus-header-btn">
                        @auth
                            <a href="{{ route('dashboard') }}" class="consbus-login-btn">Dashboard</a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="consbus-logout-btn">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="consbus-login-btn">Login</a>
                            <a href="{{ route('register') }}" class="consbus-register-btn">Register</a>
                        @endauth
                        <a href="#contact" class="consbus-quote-btn">Get A Quote</a>
                    </div>
                    <!-- Header Buttons End -->

                    <!-- Mobile Toggle Start -->
                    <div class="consbus-mobile-toggle d-lg-none">
                        <button class="menu-toggle" type="button">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                    <!-- Mobile Toggle End -->
                </div>
            </div>
        </header>
        <!-- Header Section End -->

        <!-- Mobile Menu Start -->
        <div class="consbus-mobile-menu">
            <div class="mobile-menu-overlay"></div>
            <div class="mobile-menu-content">
                <button class="mobile-menu-close">
                    <i class="fas fa-times"></i>
                </button>
                <nav class="mobile-nav">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#service">Service</a></li>
                        <li><a href="#case-study">Case Study</a></li>
                        <li><a href="#team">Team</a></li>
                        <li><a href="#blog">Blog</a></li>
                    </ul>
                </nav>
                <div class="mobile-auth-buttons">
                    @auth
                        <a href="{{ route('dashboard') }}" class="consbus-login-btn">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="consbus-logout-btn">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="consbus-login-btn">Login</a>
                        <a href="{{ route('register') }}" class="consbus-register-btn">Register</a>
                    @endauth
                </div>
            </div>
        </div>
        <!-- Mobile Menu End -->
