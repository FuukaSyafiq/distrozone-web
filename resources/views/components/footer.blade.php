<!-- Footer -->
<footer class="bg-gray-900 w-full text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 w-full sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <!-- Brand -->
            <div>
                <h2 class="text-2xl font-bold text-white mb-4">
                    Distro zone
                </h2>
                <p class="text-sm text-gray-400">
                    Toko ini adalah sebuah toko yang menjual berbagai macam kaos distro dengan berbagai macam model,
                    warna dan ukuran.
                </p>
            </div>

            <!-- Navigasi -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">
                    Navigasi
                </h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="hover:text-white transition">Home</a></li>
                    <li><a href="/products" class="hover:text-white transition">Produk</a></li>
                    @if (auth()->user())
                    <li><a href="/profile" class="hover:text-white transition">Profile</a></li>

                    @endif
                </ul>
            </div>

            <!-- Bantuan -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">
                    Bantuan
                </h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/faq" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="{{ \App\Helpers\Telegram::linkWithMessage() }}"
                            class="hover:text-white transition">Customer service</a></li>
                </ul>
            </div>

            <!-- Kontak & Sosial -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">
                    Hubungi Kami
                </h3>

                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-center gap-2">
                        <span>📍</span> Jln. Raya Pegangsaan Timur No.29H Kelapa Gading Jakarta

                    </li>
                    <li class="flex items-center gap-2">
                        <span>📧</span> distrozone@gmail.com
                    </li>

                </ul>

                <!-- Social Icons -->
                <div class="flex items-center gap-4 mt-5">
                    <a href="{{ \App\Helpers\Telegram::linkWithMessage() }}"
                        class="hover:text-white transition" target="_blank">
                        <x-bi-telegram class="w-8 h-8" />
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Bottom -->
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 py-4 text-center text-sm text-gray-500">
            &copy; 2025
            <span class="font-semibold text-white">Distrozone</span>.
            All rights reserved.
        </div>
    </div>
</footer>
<!-- Footer End -->