<x-app-layout>
    @php
    $user = auth()->user();
    $isVerified = $user->nik_verified === \App\Helpers\NikVerified::APPROVED;
    @endphp

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div x-data="{ 
    showModal: false, 
    photoPreview: null,
    openModal() {
        this.showModal = true;
        this.photoPreview = null;
    },
    closeModal() {
        this.showModal = false;
        this.photoPreview = null;
    }
}" @open-photo-modal.window="openModal()" x-cloak>
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
                                <button @click="openModal()" type="button"
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
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->nama }}
                                </h1>
                                <p class="text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                <div class="flex flex-wrap justify-center sm:justify-start gap-3 mt-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $isVerified
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        @if($isVerified)
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Verified
                                        @else
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.514 11.59c.75 1.334-.213 2.986-1.742 2.986H3.485c-1.53 0-2.492-1.652-1.743-2.986L8.257 3.1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Unverified
                                        @endif
                                    </span>
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

        <!-- Modal Edit Foto Profile -->

        <div>
            <!-- Backdrop -->
            <template x-if="showModal">
                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40"
                    @click="closeModal()">
                </div>
            </template>

            <!-- Modal -->
            <template x-if="showModal">
                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-90"
                    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
                    @click.self="closeModal()">

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Ubah Foto Profile
                            </h3>
                            <button @click="closeModal()" type="button"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('edit.foto.profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Preview Foto -->
                            <div class="mb-4">
                                <div class="flex justify-center">
                                    <div class="relative">
                                        <!-- Preview gambar baru -->
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview"
                                                class="w-40 h-40 rounded-full object-cover border-4 border-indigo-500"
                                                alt="Preview">
                                        </template>

                                        <!-- Gambar saat ini -->
                                        <template x-if="!photoPreview">
                                            <img src="@if(auth()->user()->foto_user){{ Storage::disk('s3')->url(auth()->user()->foto_user) }}@else{{ 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&size=160&background=6366f1&color=fff' }}@endif"
                                                class="w-40 h-40 rounded-full object-cover border-4 border-gray-300"
                                                alt="Current Photo">
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Input File -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pilih Foto Baru
                                </label>
                                <input type="file" name="foto_user" accept="image/jpeg,image/png,image/jpg" required
                                    @change="if($event.target.files.length > 0) { photoPreview = URL.createObjectURL($event.target.files[0]) }"
                                    class="block w-full text-sm text-gray-900 dark:text-gray-300
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          dark:file:bg-indigo-900 dark:file:text-indigo-200
                                          cursor-pointer border border-gray-300 dark:border-gray-600 rounded-lg">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Format: JPG, PNG, JPEG. Maksimal 2MB
                                </p>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-3">
                                <button type="button" @click="closeModal()"
                                    class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-lg transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-app-layout>