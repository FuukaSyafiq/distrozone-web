<x-app-layout>
    @php
    $user = auth()->user();
    @endphp

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="max-w-7xl mx-auto mb-4 px-4">
                <div class="bg-green-50 dark:bg-green-900 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-green-700 dark:text-green-200 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Alert Error -->
            @if($errors->any())
            <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto mb-4 px-4">
                <div class="bg-red-50 dark:bg-red-900 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            @foreach($errors->all() as $error)
                            <p class="text-red-700 dark:text-red-200 font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mb-6">
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                <div class="px-6 pb-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16">
                        <div class="relative">
                            @if (auth()->user()->foto_user)
                            <img src="{{ Storage::disk('s3')->url(auth()->user()->foto_user) }}" alt="Profile"
                                class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg object-cover">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&size=128&background=6366f1&color=fff"
                                alt="Profile"
                                class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg">
                            @endif
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-6 text-center sm:text-left flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->nama }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            <div class="flex flex-wrap justify-center sm:justify-start gap-3 mt-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ auth()->user()->status }}
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                    Member since {{ auth()->user()->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <form method="POST" action="/logout">
                                @csrf
                                <button
                                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6" x-data="{ tab: 'profile' }">
                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-2">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                        <nav class="space-y-1">
                            <a href="#" @click.prevent="tab = 'profile'"
                                :class="tab === 'profile'
                                ? 'bg-indigo-50 border-indigo-600 text-gray-900 dark:text-white font-medium'
                                : 'border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                class="flex items-center px-6 py-3 border-l-4 transition">
                                <x-heroicon-o-user class="w-5 h-5 mr-3" />
                                Profil
                            </a>

                            <a href="/orders"
                                :class="tab === 'pesanan'
                                ? 'bg-indigo-50 border-indigo-600 text-gray-900 dark:text-white font-medium'
                                : 'border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                class="flex items-center px-6 py-3 border-l-4 transition">
                                <x-heroicon-o-shopping-bag class="w-5 h-5 mr-3" />
                                Pesanan saya
                            </a>

                            <a href="/cart"
                                :class="tab === 'keranjang' 
                                ? 'bg-indigo-50 border-indigo-600 text-gray-900 dark:text-white font-medium'
                                : 'border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                class="flex items-center px-6 py-3 border-l-4 transition">
                                <x-heroicon-o-shopping-cart class="w-5 h-5 mr-3" />
                                Keranjang saya
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="space-y-6 w-full">
                    <div x-show="tab === 'profile'" x-cloak>
                        <livewire:profile-form />
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>