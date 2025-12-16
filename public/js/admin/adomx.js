// Adomx Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const headerMenuToggle = document.getElementById('header-menu-toggle');
    const sidebar = document.getElementById('adomx-sidebar');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    if (headerMenuToggle) {
        headerMenuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Navigation Toggle
    const navToggles = document.querySelectorAll('.adomx-nav-toggle');
    navToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const navItem = this.closest('.adomx-nav-item');
            const submenu = navItem.querySelector('.adomx-nav-submenu');
            const chevronDown = navItem.querySelector('.fa-chevron-down');
            const chevronUp = navItem.querySelector('.fa-chevron-up');
            
            navItem.classList.toggle('active');
            
            // Toggle chevron icons
            if (navItem.classList.contains('active')) {
                if (chevronDown) chevronDown.style.display = 'none';
                if (chevronUp) chevronUp.style.display = 'inline-block';
                if (submenu) submenu.style.maxHeight = '500px';
            } else {
                if (chevronDown) chevronDown.style.display = 'inline-block';
                if (chevronUp) chevronUp.style.display = 'none';
                if (submenu) submenu.style.maxHeight = '0';
            }
        });
    });

    // User Dropdown
    const userDropdownToggle = document.getElementById('user-dropdown-toggle');
    const userDropdownMenu = document.getElementById('user-dropdown-menu');
    const userDropdown = document.querySelector('.adomx-user-dropdown');

    if (userDropdownToggle) {
        userDropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
        }
    });

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !headerMenuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.adomx-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Chart initialization helper
function initChart(canvasId, config) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, config);
}

