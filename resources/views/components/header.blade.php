<nav class="bg-white w-full shadow-md sticky top-0 z-50">
    <div class="mx-auto w-full px-4 md:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between gap-4">

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" id="mobile-menu-button"
                    class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" id="menu-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg class="hidden h-6 w-6" id="close-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center gap-2 group">
                    <img src="{{ asset('Distrozone-logopng.png') }}" class="w-40" />
                    {{-- <h1 class="font-bold text-xl text-gray-800 hidden md:block">Distrozone</h1> --}}
                </a>
            </div>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex flex-1 max-w-xl mx-4">
                <form action="/search" method="GET" class="w-full relative">
                    <input type="text" name="q" id="searchInput"
                        class="w-full rounded-lg border-2 border-gray-200 py-2.5 pl-4 pr-12 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all"
                        placeholder="Cari kaos, merek, atau kategori..." autocomplete="off" />
                    <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white rounded-md p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Right Section: Cart & Auth -->
            <div class="flex items-center gap-3">

                <!-- Search Icon (Mobile) -->
                <button type="button" id="mobile-search-button"
                    class="md:hidden p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Shopping Cart -->
                <a href="/cart" class="relative p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors group">
                    @if (auth()->check())
                    <x-heroicon-c-shopping-cart class="w-6 h-6 group-hover:scale-110 transition-transform" />

                    @endif
                    <!-- Cart Badge -->
                    @if(auth()->check())
                    @if($cartCount > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                        {{$cartCount }}
                    </span>
                    @endif
                    @endif

                </a>

                @guest
                <!-- Guest Buttons -->
                <div class="hidden md:flex gap-2">
                    <a href="/login"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        Login
                    </a>
                    <a href="/register"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        Register
                    </a>
                </div>
                @endguest

                @auth
                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 p-1 hover:bg-gray-100 rounded-lg transition-colors">
                        @if (auth()->user() && auth()->user()->foto_user)
                        <img src="{{ Storage::disk('s3')->url(auth()->user()->foto_user) }}" alt="Profile"
                            class="w-12 h-12 rounded-full border-4 border-white dark:border-gray-800 shadow-lg object-cover">
                        @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama ?? 'User') }}&size=128&background=6366f1&color=fff"
                            alt="Profile"
                            class="w-12 h-12 rounded-full border-4 border-white dark:border-gray-800 shadow-lg">
                        @endif
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.outside="open = false" x-cloak
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1"
                        style="display: block;">

                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->nama }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        @php
                        $role = auth()->user()->role->role;
                        @endphp

                        <a href="
                                                @if($role == 'CUSTOMER')
                                                    /profile
                                                @elseif($role == 'ADMIN')
                                                    /admin
                                                @elseif($role == 'KASIR')
                                                    /kasir
                                                @else
                                                    / # fallback kalau role lain
                                                @endif
                                            "
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil Saya
                        </a>

                        @if (auth()->user()->role->role == "CUSTOMER")

                        <a href="/orders"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Pesanan Saya
                        </a>
                        @endif

                        <div class="border-t border-gray-200 mt-1 pt-1">
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth

            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div id="mobile-search" class="hidden md:hidden pb-4">
            <form action="/search" method="GET" class="relative">
                <input type="text" name="q"
                    class="w-full rounded-lg border-2 border-gray-200 py-2.5 pl-4 pr-12 focus:border-blue-500 focus:outline-none"
                    placeholder="Cari kaos..." autocomplete="off" />
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 text-white rounded-md p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="hidden md:hidden border-t border-gray-200" id="mobile-menu">
        <div class="px-4 py-3 space-y-1 bg-gray-50">
            @guest
            <a href="/login"
                class="block px-4 py-2 text-center rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 border border-gray-200">
                Login
            </a>
            <a href="/register"
                class="block px-4 py-2 text-center rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Register
            </a>
            @endguest

            @auth
            <a href="/" class="block px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-white">
                Home
            </a>
            <a href="/cart" class="block px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-white">
                Keranjang
            </a>
            <a href="/orders" class="block px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-white">
                Pesanan Saya
            </a>
            @endauth
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    }

    // Mobile search toggle
    const mobileSearchButton = document.getElementById('mobile-search-button');
    const mobileSearch = document.getElementById('mobile-search');

    if (mobileSearchButton) {
        mobileSearchButton.addEventListener('click', () => {
            mobileSearch.classList.toggle('hidden');
        });
    }
</script>