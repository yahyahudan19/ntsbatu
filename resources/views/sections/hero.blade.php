<section class="relative py-24 md:py-28 px-4 overflow-hidden min-h-[65vh] flex items-center justify-center">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=1600&amp;q=80" 
             alt="Strawberry Background"
             class="w-full h-full object-cover scale-105 opacity-90"
             onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg, #FFF0F3 0%, #FFE4E6 100%)';">
        <!-- Lighter Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/40"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-5xl mx-auto text-center animate-fade-in-up">
        
        <!-- White Glass Card for Contrast -->
        <div class="bg-white/10 backdrop-blur-md rounded-[3rem] p-10 md:p-16 border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.1)] inline-block">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 mb-6 px-5 py-2 bg-brand-red/90 text-white rounded-full shadow-lg transform rotate-[-2deg] hover:rotate-0 transition-transform">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.5 16.5c0 3.5 2 5 4.5 5s4.5-1.5 4.5-5c0-3-2-6-4.5-9.5C9.5 10.5 7.5 13.5 7.5 16.5z"/><path d="M12 7V2"/><path d="M9.5 4.5L12 7l2.5-2.5"/></svg>
                <span class="font-bold tracking-wide text-sm uppercase">Fresh from Batu</span>
            </div>

            <!-- Main Title -->
            <h1 id="hero-title" class="text-6xl md:text-7xl lg:text-8xl font-black text-white mb-6 tracking-tight font-display drop-shadow-[0_4px_4px_rgba(0,0,0,0.25)] leading-none">
                Organic,<br>
                <span class="text-brand-gold italic font-serif">Fresh & Sweet</span>
            </h1>

            <!-- Subtitle -->
            <p id="hero-subtitle" class="text-xl md:text-2xl text-white mb-10 max-w-2xl mx-auto font-medium leading-relaxed drop-shadow-md">
                100% Strawberry Premium & Murbei dari Dataran Tinggi Batu.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="#products"
                   class="group relative inline-flex items-center justify-center bg-brand-red text-white px-10 py-5 rounded-full font-bold text-lg transition-all transform hover:scale-105 hover:bg-brand-dark-red hover:shadow-xl hover:shadow-red-500/30 ring-4 ring-white/20">
                    <span>Order Now</span>
                    <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                
                <a href="#how-it-works"
                   class="inline-flex items-center justify-center px-10 py-5 rounded-full font-bold text-white bg-white/20 hover:bg-white/30 backdrop-blur border border-white/40 transition-all shadow-lg">
                   Learn More
                </a>
            </div>
        </div>

        <!-- Stats Floating -->
        <div class="mt-12 inline-flex gap-8 bg-white/90 backdrop-blur rounded-2xl px-8 py-4 shadow-xl">
            <div class="text-center">
                <p class="text-2xl font-bold text-zinc-900">100%</p>
                <p class="text-xs text-zinc-500 font-bold uppercase">Natural</p>
            </div>
            <div class="w-px bg-zinc-200"></div>
            <div class="text-center">
                <p class="text-2xl font-bold text-zinc-900">24h</p>
                <p class="text-xs text-zinc-500 font-bold uppercase">Delivery</p>
            </div>
            <div class="w-px bg-zinc-200"></div>
            <div class="text-center">
                <p class="text-2xl font-bold text-zinc-900 flex items-center justify-center gap-1">5 <svg class="w-5 h-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></p>
                <p class="text-xs text-zinc-500 font-bold uppercase">Reviews</p>
            </div>
        </div>
    </div>
</section>