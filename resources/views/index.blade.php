<x-app-layout>
    {{-- Hero Section / Carousel --}}
    <div class="relative w-full bg-gray-50 overflow-hidden">
        <div class="container mx-auto px-4 py-6">
            <div class="carousel relative group">
                <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                    <div class="flex transition-transform duration-700 ease-in-out" id="carouselSlides">
                        <div class="min-w-full relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent z-10"></div>
                            <img src="{{ asset('carousel.png') }}" alt="Promo Terbaru"
                                class="w-full h-[300px] md:h-[450px] object-cover transform hover:scale-105 transition-transform duration-1000">

                            <div class="absolute bottom-10 left-10 z-20 text-white">
                                <h2 class="text-4xl font-black italic tracking-tighter uppercase">New Arrival</h2>
                                <p class="text-lg opacity-90">Koleksi terbaru Distrozone kini tersedia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4">
        {{-- Search Bar Section - Dibuat lebih ramping & Floating --}}
        <div class="relative -mt-12 z-30 max-w-2xl mx-auto">
            <form action="/search" method="GET" class="group">
                <div class="relative">
                    <input type="text" name="q" id="searchInput"
                        class="w-full rounded-2xl border-none bg-white py-5 pl-6 pr-16 shadow-xl focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-700 placeholder-gray-400"
                        placeholder="Cari kaos favoritmu di sini..." autocomplete="off" />
                    <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-gray-900 hover:bg-orange-500 text-white rounded-xl p-3 transition-all duration-300 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
            {{-- Masukkan kode ini tepat di bawah penutup form Search Bar dan di atas Section Title --}}

            <section id="about" class="py-20">
                <div class="flex flex-col md:flex-row items-center gap-12">
                    <div class="w-full md:w-1/2 relative">
                        <div class="absolute -top-4 -left-4 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl"></div>
                        <div
                            class="relative rounded-3xl overflow-hidden shadow-2xl transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                            <img src="{{ asset('carousel.png') }}" alt="Tentang Distrozone"
                                class="w-full h-80 object-cover">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-8">
                                <span class="text-white font-black text-2xl italic tracking-tighter uppercase">Est.
                                    2024</span>
                            </div>
                        </div>
                        <div
                            class="absolute -bottom-6 -right-6 w-32 h-32 bg-orange-500 rounded-3xl -z-10 hidden md:block">
                        </div>
                    </div>

                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <span class="text-orange-500 font-bold uppercase tracking-[0.3em] text-xs">Authentic &
                                Unique</span>
                            <h2 class="text-4xl md:text-5xl font-black text-gray-900 leading-none mt-2">
                                LEBIH DARI SEKADAR <br> <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">FASHION.</span>
                            </h2>
                        </div>

                        <p class="text-gray-600 leading-relaxed text-lg">
                            <strong class="text-gray-900">Distrozone</strong> hadir untuk kamu yang bosan dengan desain
                            pasaran.
                            Kami mengkurasi koleksi kaos dan hoodie dengan grafis karakter yang ikonik, kualitas kain
                            premium, dan
                            potongan yang nyaman untuk aktivitas harianmu.
                        </p>

                        <div class="grid grid-cols-2 gap-6 pt-4">
                            <div class="border-l-4 border-orange-500 pl-4">
                                <h4 class="font-bold text-gray-900 text-xl">High Quality</h4>
                                <p class="text-sm text-gray-500">Bahan Cotton Combed premium yang adem.</p>
                            </div>
                            <div class="border-l-4 border-orange-500 pl-4">
                                <h4 class="font-bold text-gray-900 text-xl">Limited Design</h4>
                                <p class="text-sm text-gray-500">Koleksi eksklusif, tidak diproduksi massal.</p>
                            </div>
                        </div>

                        <div class="pt-4">
                            <a href="#produk"
                                class="inline-flex items-center gap-2 font-bold text-gray-900 hover:text-orange-500 transition-colors group">
                                Explore Our Collection
                                <svg class="w-5 h-5 transform group-hover:translate-x-2 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Lanjut ke Section Title Produk yang sudah dibuat sebelumnya --}}
        </div>

        {{-- Section Title --}}
        <div class="mt-16 mb-10 flex items-end justify-between border-b border-gray-100 pb-4">
            <div>
                <span class="text-orange-500 font-bold uppercase tracking-widest text-xs">Katalog Produk</span>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $title }}</h1>
            </div>
            <div class="hidden md:block">
                <span class="text-sm text-gray-400">Menampilkan {{ count($kaoss) }} Produk</span>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8 mb-20">
            @foreach($kaoss as $kaos)
            @php $variant = $kaos->variants; @endphp
            <a href="/kaos/{{ $kaos->id_kaos }}" class="group">
                <div class="bg-white rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="relative aspect-[3/4] overflow-hidden rounded-2xl bg-gray-100 shadow-sm border border-gray-50">
                        @if($variant && $variant[0]->image_path)
                        <img src="{{ Storage::disk('s3')->url($variant[0]->image_path) }}" alt="{{ $kaos->nama_kaos }}"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <svg class="w-16 h-16 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                            </svg>
                        </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span
                                class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-gray-800 shadow-sm">
                                {{ $kaos->type->type }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 pb-2 px-1">
                        <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mb-1">{{
                            $kaos->merek->merek }}</p>
                        <h3
                            class="text-md font-bold text-gray-800 line-clamp-1 group-hover:text-orange-500 transition-colors">
                            {{ $kaos->nama_kaos }}
                        </h3>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-lg font-black text-gray-900">
                                Rp{{ number_format($variant[0]->harga_jual, 0, ',', '.') }}
                            </span>
                            <div
                                class="h-8 w-8 rounded-full border border-gray-200 flex items-center justify-center group-hover:bg-gray-900 group-hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- FAQ Section - Dibuat lebih Soft --}}
    <div class="min-h-screen bg-gray-900 py-20 px-4" id="faq">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-black text-white italic mb-4 uppercase tracking-tighter">Frequently Asked
                    Questions
                </h1>
                <div class="h-1.5 w-24 bg-gradient-to-r from-orange-500 to-red-600 mx-auto rounded-full"></div>
                <p class="text-gray-400 mt-6">Segala hal yang perlu kamu tahu tentang belanja di Distro Zone.</p>
            </div>

            <div class="space-y-4">

                <details
                    class="group bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden transition-all duration-300 open:ring-2 open:ring-orange-500">
                    <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
                        <span class="text-white font-bold uppercase tracking-wide">Kapan pesanan saya dikirim?</span>
                        <span class="text-orange-500 transition-transform duration-300 group-open:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-400 leading-relaxed border-t border-gray-700 pt-4">
                        Pesanan akan dikirim setelah pembayaran terkonfirmasi, biasanya dalam 1-2 hari kerja. Kamu akan
                        menerima
                        notifikasi pengiriman.
                    </div>
                </details>

                <details
                    class="group bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden transition-all duration-300 open:ring-2 open:ring-orange-500">
                    <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
                        <span class="text-white font-bold uppercase tracking-wide">Bagaimana cara menentukan
                            ukuran?</span>
                        <span class="text-orange-500 transition-transform duration-300 group-open:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-gray-400 leading-relaxed border-t border-gray-700 pt-4">
                        Kami menyediakan Ukuran setiap kaos. Pastikan kamu mengukur lebar dada kaos
                        favoritmu di rumah dan bandingkan dengan tabel kami untuk akurasi maksimal.
                    </div>
                </details>

            
            </div>

            <div class="mt-16 text-center">
                <p class="text-gray-500 mb-6">Masih belum menemukan jawaban?</p>
                <a href="https://wa.me/628816977857" target="_blank"
                    class="text-orange-500 font-bold hover:text-red-500 transition-colors uppercase text-sm tracking-widest border-b-2 border-orange-500/30 pb-1">
                    Chat Support Kami Langsung
                </a>
            </div>
        </div>
    </div>
</x-app-layout>