<section id="products" class="py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        
        <h3 class="text-4xl font-bold text-center text-gray-900 mb-12">
            Produk Kami
        </h3>

        <div class="grid md:grid-cols-3 gap-8 mb-16">

            {{-- LOOP PRODUK --}}
            @foreach($products as $product)
            <div class="product-card bg-white rounded-xl shadow-lg overflow-hidden">

                {{-- FOTO / IKON PRODUK --}}
                @if(!empty($product['image']))
                    <img src="{{ $product['image'] }}" 
                         alt="{{ $product['name'] }}"
                         class="w-full product-image">
                @else
                    {{-- fallback untuk Murbei yang memakai emoji --}}
                    <div class="w-full product-image bg-gradient-to-br from-purple-100 to-purple-200
                                flex items-center justify-center py-12">
                        <div class="text-center">
                            <div class="text-9xl mb-4">🫐</div>
                            <p class="text-xl font-semibold text-purple-800">
                                {{ $product['name'] }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- KONTEN --}}
                <div class="p-8">

                    <h4 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ $product['name'] }}
                    </h4>

                    <p class="text-gray-600 mb-6">
                        {{ $product['description'] }}
                    </p>

                    {{-- LIST HARGA / PAKET --}}
                    <div class="space-y-3 mb-6">
                        @foreach($product['packages'] as $index => $pkg)
                            <div
                                class="flex justify-between items-center p-3 rounded-lg price-tag
                                {{ $index === count($product['packages']) - 1 ? 'bg-green-50 border-2 border-green-200' : 'bg-gray-50' }}">
                                
                                <span class="{{ $index === count($product['packages']) - 1 ? 'text-green-700 font-medium' : 'text-gray-700' }}">
                                    {{ $pkg['label'] }}
                                </span>

                                <span class="{{ $index === count($product['packages']) - 1 ? 'font-bold text-green-700' : 'font-semibold text-gray-900' }}">
                                    Rp {{ number_format($pkg['price'], 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- TOMBOL PESAN --}}
                    <a href="{{ route('checkout.show', $product['slug']) }}"
                       class="w-full block text-center btn-primary text-white py-3 rounded-lg font-semibold">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>
