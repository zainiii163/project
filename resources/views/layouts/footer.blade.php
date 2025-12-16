
<!-- Footer Start -->
<footer class="consbus-footer">
    <div class="container">
        <div class="row">
            <!-- Company Info Column -->
            <div class="col-lg-4 col-md-6">
                <div class="consbus-footer-widget">
                    <div class="footer-logo">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="20" cy="20" r="18" stroke="white" stroke-width="2" fill="none"/>
                            <path d="M12 20 Q20 12, 28 20 Q20 28, 12 20" stroke="white" stroke-width="2" fill="none"/>
                            <circle cx="20" cy="20" r="8" stroke="white" stroke-width="1.5" fill="none"/>
                        </svg>
                        <span class="footer-logo-text">Consbus</span>
                    </div>
                    <p class="footer-description">Letem accusantium doloremque, totam periam</p>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>30 Commercial Road Fratton, Australia</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>1-888-452-1505</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>hello@Consbus.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Links Column -->
            <div class="col-lg-3 col-md-6">
                <div class="consbus-footer-widget">
                    <h4 class="footer-widget-title">Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="#service">Services</a></li>
                        <li><a href="#about">About us</a></li>
                        <li><a href="#team">Team</a></li>
                        <li><a href="#blog">News</a></li>
                    </ul>
                </div>
            </div>

            <!-- Recent News Column -->
            <div class="col-lg-5 col-md-12">
                <div class="consbus-footer-widget">
                    <h4 class="footer-widget-title">Recent News</h4>
                    <div class="footer-news-list">
                        <div class="footer-news-item">
                            <div class="news-thumbnail">
                                <img src="{{ asset('assets/images/news/news-1.jpg') }}" alt="News" onerror="this.src='https://via.placeholder.com/80x80?text=News'">
                            </div>
                            <div class="news-content">
                                <span class="news-date">Dec 24, 2020</span>
                                <h5 class="news-title">Tips and tricks to boost your business today</h5>
                            </div>
                        </div>
                        <div class="footer-news-item">
                            <div class="news-thumbnail">
                                <img src="{{ asset('assets/images/news/news-2.jpg') }}" alt="News" onerror="this.src='https://via.placeholder.com/80x80?text=News'">
                            </div>
                            <div class="news-content">
                                <span class="news-date">Dec 22, 2020</span>
                                <h5 class="news-title">Advising administration of human resource</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Copyright -->
    <div class="consbus-footer-copyright">
        <div class="container">
            <p>2021 © All rights reserved by Themexriver</p>
        </div>
    </div>
</footer>
<!-- Footer End -->

<!-- Back To Top Button -->
<a href="#" class="consbus-back-to-top">
    <i class="fas fa-arrow-up"></i>
</a>
<!-- Back To Top End -->

</div>
<!-- Main Wrapper End -->

<!-- JS -->
<!-- jQuery -->
<script src="{{ asset('assets/js/vendor/jquery-3.5.1.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Plugins JS -->
<script src="{{ asset('assets/js/plugins.min.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Consbus Custom JS -->
<script src="{{ asset('assets/js/consbus.js') }}"></script>

</body>
</html>
