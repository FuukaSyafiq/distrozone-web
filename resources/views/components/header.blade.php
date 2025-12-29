<nav class="bg-gray-100 w-full shadow-lg sticky top-0 z-40">
    <div class="mx-auto w-full px-2 md:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-around md:justify-between">
            <div class="absolute inset-y-0 left-0 flex items-center cursor-pointer sm:mr-o mr-5 md:hidden">
                <!-- Mobile menu button-->
                <button type="button"
                    class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon when menu is closed. -->
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <!-- Icon when menu is open. -->
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Madura Logo -->
            <div class="flex items-center md:w-1/5 w-2/5 sm:block mx-auto justify-center md:justify-between cursor-pointer"
                id="goHome">
                <div class="flex items-center mr-3 justify-center">
                    {{-- <img class="mt-2 max-w-24 text-center" src="/assets/logomadura-removebg-preview.png" --}}
                        {{-- alt="Your Company"> --}}
                    <h2 class="font-bold text-md -ml-7 md:block hidden">Distrozone
                    </h2>
                </div>
            </div>

            <!-- Input Search -->
            <div class="flex items-center flex-col justify-center w-4/5 sm:w-3/5 md:w-5/5 relative">
                <div class="bg-white flex w-full rounded-lg  border border-black ">
                    <input id="searchInput" class="w-full rounded-md border-none py-sm-2 py-1 px-1 px-sm-2" autocomplete="on"
                        placeholder="Search ..." />
                    <x-bi-search class="hover:cursor-pointer mx-[-10px] m-auto" />
                </div>
            </div>

            @guest
            <div class="flex gap-3 mx-2">
                <a href="/login"
                    class="rounded-md bg-gray-900 text-xs sm:text-md px-3 py-2 font-medium text-white">
                    Login
                </a>
                <a href="/register"
                    class="rounded-md bg-gray-900 text-xs sm:text-md px-3 py-2 font-medium text-white">
                    Register
                </a>
            </div>
        @endguest
        
        @auth
            <div class="relative mx-2" x-data="{ open: false }">
                <!-- Avatar -->
                <button @click="open = !open" class="flex items-center focus:outline-none">
                    <img
                        src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}"
                        alt="avatar"
                        class="w-8 h-8 rounded-full border"
                    >
                </button>
        
                <!-- Dropdown -->
                <div
                    x-show="open"
                    @click.outside="open = false"
                    class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border"
                >
                    <a href="/profile"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profile
                    </a>
        
                    <form method="POST" action="/logout">
                        @csrf
                        <button
                            type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth
        
        </div>

    </div>
    </div>

    <!--Navbar Handphone devices -->
    <div class="hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2 absolute bg-white shadow-md w-full">
            <a href="/"
                class="block rounded-md hover:cursor-pointer bg-gray-900 text-xs sm:text-md px-3 py-2  text-center font-medium text-white"
                aria-current="page">Home</a>
            <a href="/login"
                class="block rounded-md  text-center hover:cursor-pointer bg-gray-900 text-xs sm:text-md px-3 py-2  font-medium text-white"
                aria-current="page">Login</a>
            <a href="/register"
                class="block rounded-md  text-center hover:cursor-pointer bg-gray-900 px-3 py-2  text-xs sm:text-md font-medium text-white"
                aria-current="page">Register</a>
        </div>
    </div>
</nav>