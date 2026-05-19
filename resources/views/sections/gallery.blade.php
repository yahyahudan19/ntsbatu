<section id="gallery" class="py-24 px-4 bg-gradient-to-b from-white to-zinc-50">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <h3 class="text-4xl lg:text-5xl font-bold text-zinc-900 mb-4 font-display">Our Gallery</h3>
          <p class="text-lg text-zinc-500">Intip kesegaran langsung dari kebun kami di dataran tinggi Batu.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-6 mb-6">
          <!-- Large Featured Image -->
          <div class="md:col-span-2 md:row-span-2">
            <div class="relative h-full min-h-[400px] rounded-3xl overflow-hidden shadow-lg group">
                <img src="{{asset('images/gallery/foto3.jpg')}}"
                     alt="Strawberry Farm Batu" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <h4 class="text-3xl font-bold text-white mb-2">Kebun Strawberry Kami</h4>
                    <p class="text-zinc-200">Ditanam dengan cinta dan metode organik modern.</p>
                </div>
            </div>
          </div>
          
          <!-- Small Images Column -->
          <div class="space-y-6">
            <div class="relative h-48 rounded-3xl overflow-hidden shadow-md group">
                <img src="{{asset('images/gallery/foto4.jpg')}}"
                     alt="Fresh Strawberries Close-up" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     onerror="this.src='https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&amp;q=80';">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <p class="font-bold text-white text-sm">Fresh Picked</p>
                </div>
            </div>
            
            <div class="relative h-48 rounded-3xl overflow-hidden shadow-md group">
                <img src="{{asset('images/gallery/foto5.jpg')}}"
                     alt="Strawberry Harvest" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     onerror="this.src='https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&amp;q=80';">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <p class="font-bold text-white text-sm">Proses Sortir</p>
                </div>
            </div>

            <div class="relative h-48 rounded-3xl overflow-hidden shadow-md group">
                <img src="{{asset('images/gallery/foto-frozen.jpg')}}"
                     alt="Strawberry Frozen" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     onerror="this.src='https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&amp;q=80';">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4">
                     <p class="font-bold text-white text-sm">Premium Frozen</p>
                </div>
            </div>
          </div>
        </div>
        
        <!-- Bottom Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
           <div class="relative h-48 rounded-3xl overflow-hidden shadow-md group">
               <img src="{{asset('images/products/foto-frozen.jpg')}}"
                    alt="Frozen Strawberries" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    onerror="this.src='https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&amp;q=80';">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
           </div>
           <div class="relative h-48 rounded-3xl overflow-hidden shadow-md group">
               <img src="{{asset('images/products/foto6.jpg')}}" 
                    alt="Fresh Berries" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    onerror="this.src='https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&amp;q=80';">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
           </div>
           <div class="relative h-48 rounded-3xl overflow-hidden shadow-md group">
               <img src="https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&amp;q=80" 
                    alt="Berry Packaging" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    onerror="this.src='https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&amp;q=80';">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
           </div>
           <div class="relative h-48 rounded-3xl overflow-hidden shadow-md group">
               <img src="https://images.unsplash.com/photo-1495570689269-d883b1224443?w=600&amp;q=80" 
                    alt="Farm Fresh" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    onerror="this.src='https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&amp;q=80';">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
           </div>
        </div>
      </div>
</section>