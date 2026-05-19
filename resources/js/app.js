// Mobile Menu Toggle Logic
window.toggleMobileMenu = function() {
    const mobileMenu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    const hamburger = document.getElementById('hamburger');

    if (mobileMenu && overlay) {
        if (mobileMenu.classList.contains('translate-x-full')) {
            // Open menu
            mobileMenu.classList.remove('translate-x-full');
            overlay.classList.remove('opacity-0', 'invisible');
            if (hamburger) hamburger.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            // Close menu
            window.closeMobileMenu();
        }
    }
}

// Close Mobile Menu Logic
window.closeMobileMenu = function() {
    const mobileMenu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    const hamburger = document.getElementById('hamburger');

    if (mobileMenu && overlay) {
        mobileMenu.classList.add('translate-x-full');
        overlay.classList.add('opacity-0', 'invisible');
        if (hamburger) hamburger.classList.remove('active');
        document.body.style.overflow = '';
    }
}
