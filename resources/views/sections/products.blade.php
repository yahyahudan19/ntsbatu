<section id="products" class="py-24 px-4 bg-zinc-50">
    <div class="max-w-7xl mx-auto">
        
        <div class="text-center mb-16">
            <h3 class="text-5xl font-bold text-zinc-900 mb-4 font-display tracking-tight">
                Explore Our Fresh Picks
            </h3>
            <p class="text-xl text-zinc-500 max-w-2xl mx-auto">
                From antioxidant-rich berries to vitamin-packed citrus — discover handpicked fruits for every mood and season.
            </p>
        </div>

        <!-- Filter Tags (Visual only for now) -->
        <div class="flex justify-center gap-4 mb-12 flex-wrap">
            <button class="px-6 py-2 rounded-full bg-brand-gold text-brand-dark-red font-bold shadow-md hover:scale-105 transition-transform">All</button>
            <button class="px-6 py-2 rounded-full bg-white text-zinc-600 font-medium hover:bg-zinc-100 hover:text-brand-dark-red transition-colors border border-zinc-200">Fresh</button>
            <button class="px-6 py-2 rounded-full bg-white text-zinc-600 font-medium hover:bg-zinc-100 hover:text-brand-dark-red transition-colors border border-zinc-200">Frozen</button>
            <button class="px-6 py-2 rounded-full bg-white text-zinc-600 font-medium hover:bg-zinc-100 hover:text-brand-dark-red transition-colors border border-zinc-200">Bundles</button>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
            {{-- LOOP PRODUK --}}
            @foreach($products as $product)
            <div class="group product-card bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden hover:shadow-[0_20px_50px_rgb(0,0,0,0.1)] transition-all duration-300 border border-zinc-100 flex flex-col h-full">

                {{-- FOTO / IKON PRODUK --}}
                <div class="relative aspect-[4/3] overflow-hidden bg-zinc-50 p-6 flex items-center justify-center">
                    @if(!empty($product['image']))
                        <img src="{{ $product['image'] }}" 
                             alt="{{ $product['name'] }}"
                             class="w-full h-full object-cover rounded-2xl group-hover:scale-110 transition-transform duration-500">
                    @else
                        {{-- fallback --}}
                        <div class="w-full h-full flex items-center justify-center bg-purple-50 rounded-2xl group-hover:bg-purple-100 transition-colors">
                            <svg class="w-24 h-24 text-purple-400 filter drop-shadow-xl transform group-hover:scale-110 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 17 3.5s1.5 3.5 1.5 7.5A7 7 0 0 1 11 20z"/><path d="M11 20V7"/></svg>
                        </div>
                    @endif
                    
                    <!-- NEW Badge -->
                    @if($loop->first || $loop->iteration == 2)
                    <div class="absolute top-6 left-6 bg-white/90 backdrop-blur px-3 py-1 rounded-full border border-zinc-100 shadow-sm">
                        <span class="text-xs font-bold text-brand-green uppercase tracking-wider">New Content</span>
                    </div>
                    @endif
                    
                    <!-- Wishlist Btn -->
                    <button class="absolute top-6 right-6 p-2 bg-white rounded-full shadow-md text-zinc-400 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                </div>

                {{-- KONTEN --}}
                <div class="p-8 flex flex-col flex-grow">

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-2xl font-bold text-zinc-900 group-hover:text-brand-red transition-colors">
                                {{ $product['name'] }}
                            </h4>
                            <p class="text-sm text-zinc-500 mt-1 font-medium">100% Organic</p>
                        </div>
                        <div class="bg-zinc-100 px-3 py-1 rounded-lg">
                             <span class="text-xs font-bold text-zinc-600">{{ count($product['packages']) }} Variant</span>
                        </div>
                    </div>

                    <p class="text-zinc-600 mb-6 line-clamp-2">
                        {{ $product['description'] }}
                    </p>

                    <div class="mt-auto space-y-4">
                        {{-- Shows Lowest Price --}}
                        <div class="flex items-baseline gap-2">
                             <span class="text-sm text-zinc-500">Starts from</span>
                             <span class="text-3xl font-bold text-brand-dark-red">
                                 Rp {{ number_format($product['packages'][0]['price'], 0, ',', '.') }}
                             </span>
                        </div>

                        {{-- TOMBOL PESAN --}}
                        <a href="{{ route('checkout.show', $product['slug']) }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-brand-red text-white py-4 rounded-xl font-bold text-lg hover:bg-brand-dark-red hover:text-white transition-all transform active:scale-95 shadow-xl shadow-red-100 hover:shadow-red-200">
                            <span>Add to Cart</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-16 text-center">
             <a href="#" class="inline-flex items-center gap-2 text-zinc-500 hover:text-brand-red font-semibold transition-colors">
                <span>View Full Catalog</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
             </a>
        </div>
    </div>
</section>
