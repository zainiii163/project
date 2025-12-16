@extends('layouts.main')

@section("content")

<!-- Hero Section Start -->
<section class="consbus-hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="consbus-hero-content">
                    <span class="consbus-hero-subtitle">We are Consbus</span>
                    <h1 class="consbus-hero-title">World Leading<br>Digital Agency</h1>
                    <p class="consbus-hero-text">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariat occaecat cupidatat mque laudantium</p>
                    <a href="#about" class="consbus-btn-primary">About Us</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="consbus-hero-image">
                    <!-- Illustration will be added via CSS or image -->
                    <div class="consbus-hero-illustration"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Stay Connected Hero Section Start -->
<section class="consbus-stay-connected-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="consbus-stay-content">
                    <span class="consbus-hero-subtitle">STAY AHEAD OF OTHERS</span>
                    <h1 class="consbus-hero-title">Stay Connected</h1>
                    <p class="consbus-hero-text">Duis aute irure dolor in reprehenderit in voluptate velit esse occaecat cupidatat mque laudantium</p>
                    <div class="consbus-social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-behance"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="consbus-email-subscribe">
                    <input type="email" placeholder="Your email here" class="consbus-email-input">
                    <button type="submit" class="consbus-email-submit">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Stay Connected Hero Section End -->

<!-- Expand Business Section Start -->
<section class="consbus-expand-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="consbus-expand-illustration">
                    <!-- Illustration placeholder -->
                </div>
            </div>
            <div class="col-lg-6">
                <div class="consbus-expand-content">
                    <span class="consbus-section-subtitle">STAY AHEAD OF OTHERS</span>
                    <h2 class="consbus-section-title">Expand your business everywhere</h2>
                    <p class="consbus-section-text">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariat occaecat cupidatat mque laudantium, totam rem ape non proident, sunt in culpa qui officia deserunt mollit</p>
                    
                    <div class="consbus-feature-highlights">
                        <div class="feature-highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span>Fast growing brand in current time</span>
                        </div>
                        <div class="feature-highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <span>Full professional to grow business</span>
                        </div>
                    </div>
                    
                    <ul class="consbus-feature-list">
                        <li><i class="fas fa-arrow-right"></i> Necat cupidatat mque laudantium, totam rem ape non proident, sunt</li>
                        <li><i class="fas fa-arrow-right"></i> Anim id est laborum. Sed ut perspiciatis unde omnis iste natus</li>
                        <li><i class="fas fa-arrow-right"></i> Keror sit voluptatem accusantium doloremque laudanti</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Expand Business Section End -->

<!-- What We Do Section Start -->
<section class="consbus-what-we-do-section" id="about">
    <div class="container">
        <div class="consbus-section-header text-center">
            <span class="consbus-section-subtitle">OUR SERVICE</span>
            <h2 class="consbus-section-title">What We Do</h2>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="consbus-service-box">
                    <div class="service-box-icon service-icon-purple">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h3 class="service-box-title">Website Development</h3>
                    <p class="service-box-text">Duis aute irure dolor in reprehenderit in cupie magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi</p>
                    <a href="#" class="service-box-link">→ View Service</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="consbus-service-box">
                    <div class="service-box-icon service-icon-teal">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="service-box-title">Mobile Application</h3>
                    <p class="service-box-text">Duis aute irure dolor in reprehenderit in cupie magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi</p>
                    <a href="#" class="service-box-link">→ View Service</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="consbus-service-box">
                    <div class="service-box-icon service-icon-blue">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="service-box-title">Digital Marketing</h3>
                    <p class="service-box-text">Duis aute irure dolor in reprehenderit in cupie magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi</p>
                    <a href="#" class="service-box-link">→ View Service</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="consbus-service-box">
                    <div class="service-box-icon service-icon-orange">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="service-box-title">Online Security</h3>
                    <p class="service-box-text">Duis aute irure dolor in reprehenderit in cupie magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi</p>
                    <a href="#" class="service-box-link">→ View Service</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- What We Do Section End -->

<!-- Statistics Section Start -->
<section class="consbus-stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="consbus-stat-item">
                    <h3 class="stat-number">100+</h3>
                    <p class="stat-label">WordPress Themes</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="consbus-stat-item">
                    <h3 class="stat-number">100+</h3>
                    <p class="stat-label">WordPress Themes</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="consbus-stat-item">
                    <h3 class="stat-number">3M</h3>
                    <p class="stat-label">Active Installation</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Statistics Section End -->

<!-- Services Section Start -->
<section class="consbus-services-section" id="service">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="consbus-service-card service-card-1">
                    <div class="service-icon">
                        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="10" y="15" width="15" height="15" stroke="white" stroke-width="2" fill="none"/>
                            <rect x="35" y="15" width="15" height="15" stroke="white" stroke-width="2" fill="none"/>
                            <rect x="35" y="40" width="15" height="15" stroke="white" stroke-width="2" fill="none"/>
                            <line x1="25" y1="22.5" x2="35" y2="22.5" stroke="white" stroke-width="2"/>
                            <line x1="50" y1="30" x2="50" y2="40" stroke="white" stroke-width="2"/>
                        </svg>
                    </div>
                    <h3 class="service-title">Creative Design</h3>
                    <p class="service-text">Fugiat nulla pariatur xcepteur sint occaecat cupidatat non proid ent sunt in culpa qui</p>
                    <a href="#" class="service-arrow">→</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="consbus-service-card service-card-2">
                    <div class="service-icon">
                        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="30" cy="30" r="20" stroke="white" stroke-width="2" fill="none"/>
                            <circle cx="30" cy="30" r="12" stroke="white" stroke-width="2" fill="none"/>
                            <rect x="28" y="10" width="4" height="8" fill="white"/>
                            <rect x="28" y="42" width="4" height="8" fill="white"/>
                            <rect x="10" y="28" width="8" height="4" fill="white"/>
                            <rect x="42" y="28" width="8" height="4" fill="white"/>
                        </svg>
                    </div>
                    <h3 class="service-title">Development</h3>
                    <p class="service-text">Fugiat nulla pariatur xcepteur sint occaecat cupidatat non proid ent sunt in culpa qui</p>
                    <a href="#" class="service-arrow">→</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="consbus-service-card service-card-3">
                    <div class="service-icon">
                        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M30 15 L30 25 M30 35 L30 45" stroke="white" stroke-width="3" stroke-linecap="round"/>
                            <circle cx="30" cy="30" r="15" stroke="white" stroke-width="2" fill="none"/>
                            <circle cx="30" cy="30" r="3" fill="white"/>
                        </svg>
                    </div>
                    <h3 class="service-title">Online Marketing</h3>
                    <p class="service-text">Fugiat nulla pariatur xcepteur sint occaecat cupidatat non proid ent sunt in culpa qui</p>
                    <a href="#" class="service-arrow">→</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

<!-- Why Choose Us Section Start -->
<section class="consbus-why-choose-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="consbus-why-content">
                    <span class="consbus-section-subtitle">WHY CHOOSE US</span>
                    <h2 class="consbus-section-title">Generate more sales with Consbus</h2>
                    <p class="consbus-section-text">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariat occaecat cupidatat mque laudantium</p>
                    
                    <div class="consbus-features-list">
                        <div class="consbus-feature-item">
                            <div class="feature-icon">
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="25" cy="25" r="18" stroke="#ff6b35" stroke-width="2" fill="#fff5f0"/>
                                    <path d="M15 25 L22 32 L35 18" stroke="#ff6b35" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h4>Skilled Development Community</h4>
                                <p>Duis aute irure dolor in reprehenderit in cupie magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                            </div>
                        </div>
                        
                        <div class="consbus-feature-item">
                            <div class="feature-icon">
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M25 10 L30 20 L40 22 L32 30 L33 40 L25 35 L17 40 L18 30 L10 22 L20 20 Z" stroke="#10b981" stroke-width="2" fill="#f0fdf4"/>
                                    <circle cx="25" cy="25" r="8" fill="#10b981"/>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h4>Bulletproof Security Management</h4>
                                <p>Duis aute irure dolor in reprehenderit in cupie magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                            </div>
                        </div>
                        
                        <div class="consbus-feature-item">
                            <div class="feature-icon">
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="25" cy="20" r="8" stroke="#ec4899" stroke-width="2" fill="#fdf2f8"/>
                                    <path d="M15 35 Q25 30, 35 35" stroke="#ec4899" stroke-width="2" fill="none"/>
                                    <circle cx="15" cy="10" r="3" fill="#ec4899"/>
                                    <circle cx="35" cy="10" r="3" fill="#ec4899"/>
                                    <circle cx="10" cy="25" r="3" fill="#ec4899"/>
                                    <circle cx="40" cy="25" r="3" fill="#ec4899"/>
                                    <line x1="15" y1="10" x2="25" y2="20" stroke="#ec4899" stroke-width="1"/>
                                    <line x1="35" y1="10" x2="25" y2="20" stroke="#ec4899" stroke-width="1"/>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h4>Multiway Support Integrated</h4>
                                <p>Duis aute irure dolor in reprehenderit in cupie magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="consbus-why-illustration">
                    <!-- Illustration placeholder -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Why Choose Us Section End -->

<!-- Team Section Start -->
<section class="consbus-team-section" id="team">
    <div class="container">
        <div class="consbus-section-header text-center">
            <span class="consbus-section-subtitle">TEAM PLAYERS</span>
            <h2 class="consbus-section-title">Amazing Team Members</h2>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="consbus-team-card">
                    <div class="team-image">
                        <img src="{{ asset('assets/images/team/team-1.jpg') }}" alt="Team Member" onerror="this.src='https://via.placeholder.com/300x400?text=Team+Member'">
                    </div>
                    <div class="team-info">
                        <span class="team-role">Project Manager</span>
                        <h4 class="team-name">Alison Walker</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="consbus-team-card">
                    <div class="team-image">
                        <img src="{{ asset('assets/images/team/team-2.jpg') }}" alt="Team Member" onerror="this.src='https://via.placeholder.com/300x400?text=Team+Member'">
                    </div>
                    <div class="team-info">
                        <span class="team-role">Designer</span>
                        <h4 class="team-name">Sarah Johnson</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="consbus-team-card">
                    <div class="team-image">
                        <img src="{{ asset('assets/images/team/team-3.jpg') }}" alt="Team Member" onerror="this.src='https://via.placeholder.com/300x400?text=Team+Member'">
                    </div>
                    <div class="team-info">
                        <span class="team-role">Developer</span>
                        <h4 class="team-name">Tom Anderson</h4>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-behance"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="consbus-team-card">
                    <div class="team-image">
                        <img src="{{ asset('assets/images/team/team-4.jpg') }}" alt="Team Member" onerror="this.src='https://via.placeholder.com/300x400?text=Team+Member'">
                    </div>
                    <div class="team-info">
                        <span class="team-role">Marketing</span>
                        <h4 class="team-name">Emily Davis</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Team Section End -->

@endsection
