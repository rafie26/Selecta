document.addEventListener('DOMContentLoaded', function() {
    // Improved navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Function to toggle profile dropdown
    window.toggleProfileDropdown = function() {
        const dropdown = document.getElementById('profileDropdown');
        const container = dropdown.parentElement.querySelector('.user-avatar-container');
        
        dropdown.classList.toggle('show');
        container.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const userMenu = document.querySelector('.user-menu');
        const dropdown = document.getElementById('profileDropdown');
        
        if (dropdown && userMenu && !userMenu.contains(event.target)) {
            dropdown.classList.remove('show');
            userMenu.querySelector('.user-avatar-container').classList.remove('active');
        }
    });

    // Function to open login modal - sesuai dengan auth-modal.blade.php
    window.openLoginModal = function() {
        const modal = document.getElementById('authModal');
        if (modal) {
            modal.classList.add('active');
            document.getElementById('loginModal').classList.add('active');
            document.getElementById('registerModal').classList.remove('active');
            document.body.style.overflow = 'hidden';
            console.log('Modal opened successfully');
        } else {
            console.log('Auth modal not found. Make sure to include auth-modal component.');
            window.location.href = '/login';
        }
    }
});
