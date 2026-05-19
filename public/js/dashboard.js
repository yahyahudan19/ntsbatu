/* ========================================
   Theme: apply saved preference BEFORE DOMContentLoaded
   to prevent flash of wrong theme
   ======================================== */
(function() {
    const saved = localStorage.getItem('admin-theme');
    if (saved === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
    }
})();

document.addEventListener('DOMContentLoaded', () => {
    /* ========================================
       Theme toggle (light / dark)
       ======================================== */
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';
            if (isLight) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('admin-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('admin-theme', 'light');
            }
        });
    }

    /* ========================================
       Sidebar toggle (mobile)
       ======================================== */
    const sidebar        = document.getElementById('sidebar');
    const sidebarToggle  = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('open');
        if (sidebarOverlay) sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('open');
        if (sidebarOverlay) sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

    /* ========================================
       Legacy navbar toggle (for orders/products pages)
       ======================================== */
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarMenu   = document.getElementById('navbarMenu');
    if (navbarToggle && navbarMenu) {
        navbarToggle.addEventListener('click', () => navbarMenu.classList.toggle('show'));
    }

    /* ========================================
       CountUp animation
       ======================================== */
    function animateCountUp(el, target, duration = 1200) {
        const text = el.textContent.trim();
        const isRupiah = text.startsWith('Rp');
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(eased * target);

            if (isRupiah) {
                el.textContent = 'Rp ' + current.toLocaleString('id-ID');
            } else {
                el.textContent = current.toLocaleString('id-ID');
            }

            if (progress < 1) requestAnimationFrame(update);
        }

        requestAnimationFrame(update);
    }

    // Integer stats
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10);
        if (!isNaN(target) && target > 0) animateCountUp(el, target);
    });

    // Rupiah values in stat cards
    document.querySelectorAll('.stat-earn-today .stat-value, .stat-earn-month .stat-value, .stat-earn-total .stat-value').forEach(el => {
        if (el.dataset.count) return; // already handled
        const numStr = el.textContent.replace(/[^0-9]/g, '');
        const target = parseInt(numStr, 10);
        if (!isNaN(target) && target > 0) animateCountUp(el, target, 1500);
    });

    /* ========================================
       Progress bar animation
       ======================================== */
    document.querySelectorAll('.progress-bar-inner').forEach(bar => {
        const targetWidth = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = targetWidth;
        }, 600);
    });

    /* ========================================
       Mini chart bar animation
       ======================================== */
    document.querySelectorAll('.mini-chart-bar').forEach((bar, i) => {
        const targetHeight = bar.style.height;
        bar.style.height = '0%';
        setTimeout(() => {
            bar.style.height = targetHeight;
        }, 400 + (i * 150));
    });

    /* ========================================
       Navbar background on scroll
       ======================================== */
    const topbar = document.querySelector('.topbar');
    if (topbar) {
        const mainContent = document.querySelector('.main-content');
        const scrollTarget = mainContent || window;

        function handleScroll() {
            const scrollTop = mainContent ? mainContent.scrollTop : window.scrollY;
            if (scrollTop > 20) {
                topbar.style.borderBottomColor = 'rgba(148, 163, 184, 0.12)';
                topbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
            } else {
                topbar.style.borderBottomColor = 'rgba(148, 163, 184, 0.06)';
                topbar.style.boxShadow = 'none';
            }
        }

        window.addEventListener('scroll', handleScroll, { passive: true });
    }
});
