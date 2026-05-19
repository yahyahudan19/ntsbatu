@php $linkPrefix = request()->is('/') ? '' : '/'; @endphp
<header class="fixed top-4 left-4 right-4 bg-white/80 backdrop-blur-md rounded-2xl shadow-lg z-50 transition-all duration-300 border border-white/40">
    <div class="max-w-7xl mx-auto px-6 py-3">
        <div class="flex justify-between items-center">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 group">
                <img src="/images/logos/logo.png" 
                     alt="NTS Batu Logo" 
                     class="h-10 md:h-12 w-auto object-contain transition-transform group-hover:scale-105">
            </a>

            <!-- Desktop Navigation -->
            <nav class="desktop-nav hidden md:flex gap-8 items-center">
                <a href="{{ $linkPrefix }}#products" data-nav-link="products" class="nav-link text-zinc-600 font-medium hover:text-brand-red transition-colors">Produk</a>
                <a href="{{ $linkPrefix }}#gallery" data-nav-link="gallery" class="nav-link text-zinc-600 font-medium hover:text-brand-red transition-colors">Galeri</a>
                <a href="{{ $linkPrefix }}#testimonials" data-nav-link="testimonials" class="nav-link text-zinc-600 font-medium hover:text-brand-red transition-colors">Testimoni</a>
                <a href="{{ $linkPrefix }}#contact" data-nav-link="contact" class="nav-link text-zinc-600 font-medium hover:text-brand-red transition-colors">Kontak</a>
                
                <a href="{{ $linkPrefix }}#products" class="px-6 py-2 bg-brand-red text-white rounded-full font-bold shadow-md hover:bg-brand-dark-red hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                    Pesan Sekarang
                </a>
            </nav>

            <!-- Mobile Menu Button -->
            <div class="mobile-menu-button md:hidden" onclick="toggleMobileMenu()">
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<!-- Placed outside header to ensure full-screen coverage -->
<div id="mobile-menu-overlay" class="mobile-menu-overlay fixed inset-0 bg-black/50 z-[60] opacity-0 invisible transition-all duration-300" onclick="closeMobileMenu()"></div>

<!-- Mobile Menu Sidebar -->
<div id="mobile-menu" class="mobile-menu fixed top-0 right-0 h-full w-72 bg-white shadow-2xl z-[70] transform translate-x-full transition-transform duration-300 flex flex-col">
    <div class="p-6 flex flex-col h-full">
        <div class="flex justify-between items-center mb-8">
            <span class="text-xl font-bold font-display text-zinc-800">Menu</span>
            <button onclick="closeMobileMenu()" class="p-2 -mr-2 text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <nav class="flex flex-col gap-2">
            <a href="{{ $linkPrefix }}#products" data-nav-link="products" onclick="closeMobileMenu()" class="nav-link text-lg font-medium text-zinc-600 hover:text-brand-red hover:bg-zinc-50 px-4 py-3 rounded-xl transition-all">Produk</a>
            <a href="{{ $linkPrefix }}#gallery" data-nav-link="gallery" onclick="closeMobileMenu()" class="nav-link text-lg font-medium text-zinc-600 hover:text-brand-red hover:bg-zinc-50 px-4 py-3 rounded-xl transition-all">Galeri</a>
            <a href="{{ $linkPrefix }}#testimonials" data-nav-link="testimonials" onclick="closeMobileMenu()" class="nav-link text-lg font-medium text-zinc-600 hover:text-brand-red hover:bg-zinc-50 px-4 py-3 rounded-xl transition-all">Testimoni</a>
            <a href="{{ $linkPrefix }}#contact" data-nav-link="contact" onclick="closeMobileMenu()" class="nav-link text-lg font-medium text-zinc-600 hover:text-brand-red hover:bg-zinc-50 px-4 py-3 rounded-xl transition-all">Kontak</a>
        </nav>

        <div class="mt-auto pt-6 border-t border-zinc-100">
            <a href="{{ $linkPrefix }}#products" onclick="closeMobileMenu()" class="block w-full text-center px-6 py-4 bg-brand-red text-white rounded-xl font-bold shadow-md hover:bg-brand-dark-red transition-colors">
                Pesan Sekarang
            </a>
            <p class="text-center text-xs text-zinc-400 mt-4">NTS Batu &copy; 2025</p>
        </div>
    </div>
</div>

<script>
    // Inline script to ensure functionality works independently of app.js build
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        const hamburger = document.getElementById('hamburger');
        
        if (menu.classList.contains('translate-x-full')) {
            // Open
            menu.classList.remove('translate-x-full');
            overlay.classList.remove('opacity-0', 'invisible');
            if(hamburger) hamburger.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            closeMobileMenu();
        }
    }

    function closeMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        const hamburger = document.getElementById('hamburger');

        menu.classList.add('translate-x-full');
        overlay.classList.add('opacity-0', 'invisible');
        if(hamburger) hamburger.classList.remove('active');
        document.body.style.overflow = '';
    }
</script>

<script>
    // Scroll Spy — highlights active nav link based on visible section
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('[data-nav-link]');

        if (sections.length === 0 || navLinks.length === 0) return;

        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -60% 0px',
            threshold: 0
        };

        function setActiveLink(sectionId) {
            navLinks.forEach(link => {
                if (link.dataset.navLink === sectionId) {
                    link.classList.add('text-brand-red', 'font-bold');
                    link.classList.remove('text-zinc-600');
                } else {
                    link.classList.remove('text-brand-red', 'font-bold');
                    link.classList.add('text-zinc-600');
                }
            });
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setActiveLink(entry.target.id);
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));
    });
</script>
