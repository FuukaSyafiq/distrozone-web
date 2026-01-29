<div class="w-full">
    <div x-data="{
        quantity: {{ $quantity }},
        maxStock: {{ $variant->stok_kaos }},
        pricePerItem: {{ $variant->harga_jual }},
        originalPrice: {{ $variant->harga_jual }},
        increment() {
            if (this.quantity < this.maxStock) {
                this.quantity++;
                this.$dispatch('quantity-updated', this.quantity);
            }
        },
        decrement() {
            if (this.quantity > 1) {
                this.quantity--;
                this.$dispatch('quantity-updated', this.quantity);
            }
        },
    }" class="bg-white border-2 border-gray-200 rounded-xl p-6"
        x-on:quantity-updated="$wire.set('quantity', $event.detail)">
        <h3 class="font-bold text-gray-900 mb-4">Atur jumlah dan catatan</h3>

        <!-- Selected Product Info -->
        <div class="flex gap-3 mb-4 pb-4 border-b">
            @if($variant->image_path)
            <img src="{{ Storage::url($variant->image_path) }}" alt="{{ $kaos->nama_kaos }}"
                class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
            @endif
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-700">
                    {{ $kaos->nama_kaos }}
                </p>
                <p class="text-sm font-medium text-gray-700">
                    Warna: {{ $variant->warna->label }}
                </p>
                <p class="text-sm font-medium text-gray-700">
                   Ukuran: {{ $variant->ukuran->ukuran }}
                </p>
            </div>
        </div>

        <!-- Quantity Selector -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center border-2 border-gray-200 rounded-lg">
                <button @click="decrement" :disabled="quantity <= 1"
                    :class="quantity <= 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50'"
                    class="px-3 py-2 transition-colors">
                    -
                </button>
                <input type="number" x-model.number="quantity" min="1" :max="maxStock" min="1" @input="quantity"
                    class="w-16 text-center border-x-2 border-gray-200 py-2 focus:outline-none">
                <button @click="increment" :disabled="quantity >= maxStock"
                    :class="quantity >= maxStock ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50'"
                    class="px-3 py-2 transition-colors">
                    +
                </button>
            </div>
            <div class="text-sm text-gray-600">
                Stok: <span class="font-semibold">{{ number_format($variant->stok_kaos) }}</span>
            </div>
        </div>

        <!-- Price Summary -->
        <div class="mb-4 pb-4 border-b">
            <div class="flex justify-between items-baseline">
                <span class="text-gray-700">Subtotal</span>
                <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($quantity * $variant->harga_jual, 0,
                    ',', '.') }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($variant->stok_kaos > 0)
        <div class="space-y-3">
            {{-- @php
            print_r($quantity * $kaos->harga_jual);
            @endphp --}}
            <form action="{{ route('beli.langsung.check', $variant->id) }}" method="POST" class="mt-6">
                @csrf

                <input type="hidden" name="quantity" value="{{ $quantity}}">


                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Beli langsung
                </button>
            </form>

            <livewire:add-cart :variant="$variant" :quantity="$quantity"
                wire:key="add-cart-{{ $variant->id }}{{$quantity  }}" />
        </div>
        @else
        <button disabled class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed">
            Stok Habis
        </button>
        @endif

        <!-- Additional Actions -->
        <div class="flex gap-3 mt-4">
            <a href="https://wa.me/628816977857" target="_blank"
                class="flex-1 flex items-center justify-center gap-2 py-2 px-4 border-2 border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5" />
                <span class="text-sm font-medium">Chat</span>
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('redirect-login', () => {
        setTimeout(() => {
            window.location.href = "{{ route('login') }}";
        }, 700);
    });
</script>
