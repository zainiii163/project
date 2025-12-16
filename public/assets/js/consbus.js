// Consbus Custom JavaScript

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Mobile Menu Toggle
        $('.consbus-mobile-toggle button').on('click', function() {
            $('.consbus-mobile-menu').addClass('active');
            $('.mobile-menu-overlay').addClass('active');
            $('body').css('overflow', 'hidden');
        });

        $('.mobile-menu-close, .mobile-menu-overlay').on('click', function() {
            $('.consbus-mobile-menu').removeClass('active');
            $('.mobile-menu-overlay').removeClass('active');
            $('body').css('overflow', '');
        });

        // Back to Top Button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.consbus-back-to-top').addClass('show');
            } else {
                $('.consbus-back-to-top').removeClass('show');
            }
        });

        $('.consbus-back-to-top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 800);
        });

        // Smooth Scrolling for Anchor Links
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 1000);
                
                // Close mobile menu if open
                $('.consbus-mobile-menu').removeClass('active');
                $('.mobile-menu-overlay').removeClass('active');
                $('body').css('overflow', '');
            }
        });

        // Header Scroll Effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.consbus-header-section').css('box-shadow', '0 4px 20px rgba(0, 0, 0, 0.15)');
            } else {
                $('.consbus-header-section').css('box-shadow', '0 2px 10px rgba(0, 0, 0, 0.1)');
            }
        });

        // Set Active Navigation Item on Scroll
        $(window).scroll(function() {
            var scrollDistance = $(window).scrollTop() + 100;
            
            $('section[id]').each(function(i) {
                if ($(this).position().top <= scrollDistance) {
                    $('.consbus-nav-menu a.active').removeClass('active');
                    $('.consbus-nav-menu a[href="#' + $(this).attr('id') + '"]').addClass('active');
                }
            });
        });

        // Email Subscription
        $('.consbus-email-submit').on('click', function(e) {
            e.preventDefault();
            var email = $('.consbus-email-input').val();
            if (email && email.includes('@')) {
                alert('Thank you for subscribing! We will contact you soon.');
                $('.consbus-email-input').val('');
            } else {
                alert('Please enter a valid email address.');
            }
        });

        // Enter key for email input
        $('.consbus-email-input').on('keypress', function(e) {
            if (e.which === 13) {
                $('.consbus-email-submit').click();
            }
        });

    });

})(jQuery);

