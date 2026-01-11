<div
    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300">
    <!-- Header Section -->
    <div
        class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-750 px-6 py-3 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    Added on <span class="font-medium">{{ $keranjang->created_at->format('d M Y, H:i') }}</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" wire:model="selectedItems" value="{{ $keranjang->id_keranjang_detail }}" wire:model="selected"
                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <span class="text-xs text-gray-500 dark:text-gray-400">Select</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="flex flex-col sm:flex-row gap-6">
            <!-- Product Image -->
            <div class="flex-shrink-0">
                <div class="relative group">
                    <img src="{{ Storage::disk('s3')->url($keranjang->kaos->image[0]->path) }}"
                        alt="{{ $keranjang->kaos->nama_kaos }}"
                        class="w-32 h-32 rounded-xl object-cover shadow-md group-hover:shadow-xl transition-shadow duration-300">
                    <div
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition-all duration-300">
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="flex-1 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ $keranjang->kaos->nama_kaos }}
                        </h3>

                        <!-- Product Meta Info -->
                        <div class="flex flex-wrap gap-3 mb-4">
                            @if(isset($keranjang->kaos->ukuran))
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                                Size: {{ $keranjang->kaos->ukuran }}
                            </span>
                            @endif
                            @if(isset($keranjang->kaos->warna))
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z"
                                        clip-rule="evenodd" />
                                </svg>
                                Color: {{ $keranjang->kaos->warna }}
                            </span>
                            @endif
                        </div>

                        <!-- Price Section -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Price per item:</span>
                                <span class="text-base font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($keranjang->harga_satuan, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Quantity:</span>
                                <div
                                    class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                                    <button wire:click="decrementQuantity({{ $keranjang->id_keranjang_detail }})"
                                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                        @if($qty <= 1) disabled @endif>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                    </button>
                                    <input type="number" wire:model.blur="quantities.{{ $keranjang->id_keranjang_detail }}"
                                        value="{{ $qty }}" min="1"
                                        class="w-16 text-center border-0 border-x border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <button wire:click="incrementQuantity({{ $keranjang->id_keranjang_detail }})"
                                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subtotal Section -->
                    <div class="flex flex-col items-end gap-3">
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Subtotal</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <button wire:click="removeItem({{ $keranjang->id_keranjang_detail }})"
                                wire:confirm="Are you sure you want to remove this item from your cart?"
                                class="p-2 text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
                                title="Remove from cart">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section (Stock Warning or Notes) -->
    @if(isset($keranjang->kaos->stok_kaos) && $keranjang->kaos->stok_kaos < 5) <div
        class="bg-yellow-50 dark:bg-yellow-900/20 border-t border-yellow-200 dark:border-yellow-800 px-6 py-3">
        <div class="flex items-center gap-2 text-yellow-800 dark:text-yellow-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <p class="text-sm font-medium">Only {{ $keranjang->kaos->stok_kaos }} items left in stock!</p>
        </div>
</div>
@endif
</div>