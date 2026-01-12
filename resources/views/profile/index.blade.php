<x-app-layout>
    @php
    $user = auth()->user();
    $isVerified = $user->nik_verified === \App\Helpers\NikVerified::APPROVED;
    @endphp
    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mb-6">
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                <div class="px-6 pb-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16">
                        <div class="relative">
                            @if (auth()->user()->foto_user)
                            <img src="{{ Storage::disk('s3')->url(auth()->user()->foto_user) }}" alt="Profile"
                                class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->nama }}&size=128&background=6366f1&color=fff"
                                alt="Profile"
                                class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg">
                            @endif
                            <button
                                class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-full shadow-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-6 text-center sm:text-left flex-1">
                            <h1 class="text-3xl font-bold text-white">{{ auth()->user()->nama }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            <div class="flex flex-wrap justify-center sm:justify-start gap-3 mt-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                                {{ $isVerified
                                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">

                                    @if($isVerified)
                                    {{-- CHECK ICON --}}
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Verified
                                    @else
                                    {{-- WARNING ICON --}}
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.514 11.59c.75 1.334-.213 2.986-1.742 2.986H3.485c-1.53 0-2.492-1.652-1.743-2.986L8.257 3.1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Unverified
                                    @endif
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-green bg-green">
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

            <div class="grid grid-cols-1  gap-6" x-data="{ tab: 'profile' }">
                <!-- Sidebar -->
                <div class="lg:col-span-1  space-y-2">
                    <!-- Navigation Menu -->

                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                        <nav class="space-y-1">
                            <a href="#" @click.prevent="tab = 'profile'" :class="tab === 'profile'
                                                                ? 'bg-indigo-50 border-indigo-600 text-gray-900 font-medium'
                                                                : 'border-transparent text-gray-700 hover:bg-gray-50'"
                                class="flex items-center px-6 py-3 border-l-4">
                                <x-heroicon-o-user class="w-5 h-5 mr-3" />
                                Profil
                            </a>

                            <a href="/orders" :class="tab === 'pesanan'
                                                                ? 'bg-indigo-50 border-indigo-600 text-gray-900 font-medium'
                                                                : 'border-transparent text-gray-700 hover:bg-gray-50'"
                                class="flex items-center px-6 py-3 border-l-4">
                                <x-heroicon-o-shopping-bag class="w-5 h-5 mr-3" />
                                Pesanan saya
                            </a>
                            <a href="/cart" :class="tab === 'keranjang' ? 'bg-indigo-50 border-indigo-600 text-gray-900 font-medium'
                                                        : 'border-transparent text-gray-700 hover:bg-gray-50'"
                                class="flex items-center px-6 py-3 border-l-4">
                                <x-heroicon-o-shopping-cart class="w-5 h-5 mr-3" />
                                Keranjang saya
                            </a>
                            <a href="/transaction" :class="tab === 'transactions'
                                                                ? 'bg-indigo-50 border-indigo-600 text-gray-900 font-medium'
                                                                : 'border-transparent text-gray-700 hover:bg-gray-50'"
                                class="flex items-center px-6 py-3 border-l-4">
                                <x-heroicon-o-clock class="w-5 h-5 mr-3" />
                                Transaksi saya
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class=" space-y-6 w-full">
                    <!-- Personal Information -->
                    <!-- PROFILE -->
                    <div x-show="tab === 'profile'" x-cloak>
                        <livewire:profile-form />
                    </div>

                    <!-- PESANAN -->
                    {{-- <div x-show="tab === 'pesanan'" x-cloak>
                        <livewire:pesanansaya />
                    </div>

                    <!-- TRANSACTIONS -->
                    <div x-show="tab === 'transactions'" x-cloak>
                        <livewire:transactionhistory />
                    </div>

                    <!-- KERANJANG -->
                    <div x-show="tab === 'keranjang'" x-cloak>
                        <livewire:keranjangsaya />
                    </div> --}}




                </div>
            </div>
        </div>
    </div>
</x-app-layout>