<div class="w-full">
    <div x-data="{
        quantity: {{ $quantity }},
        maxStock: {{ $variant->stok_kaos }},
        pricePerItem: {{ $kaos->harga_jual }},
        originalPrice: {{ $kaos->harga_jual }},
        get subtotal() {
            return this.quantity * this.pricePerItem;
        },
        get originalSubtotal() {
            return this.originalPrice ? this.quantity * this.originalPrice : null;
        },
        increment() {
            if (this.quantity < this.maxStock) {
                this.quantity++;
                @this.set('quantity', this.quantity);
            }
        },
        decrement() {
            if (this.quantity > 1) {
                this.quantity--;
                @this.set('quantity', this.quantity);
            }
        },
        formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(price).replace('IDR', 'Rp');
        }
    }" class="bg-white border-2 border-gray-200 rounded-xl p-6">
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
                <input type="number" x-model.number="quantity" min="1" :max="maxStock"
                    @input="quantity = Math.max(1, Math.min(maxStock, parseInt($event.target.value) || 1)); @this.set('quantity', quantity)"
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
                <span class="text-2xl font-bold text-gray-900" x-text="formatPrice(subtotal)"></span>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($variant->stok_kaos > 0)
        <div class="space-y-3">
            <livewire:beli-langsung :variant="$variant" :quantity="$quantity" wire:key="add-cart-{{ $variant->id }}" />
            <livewire:add-cart :variant="$variant" :quantity="$quantity" wire:key="add-cart-{{ $variant->id }}" />
        </div>
        @else
        <button disabled class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed">
            Stok Habis
        </button>
        @endif

        <!-- Additional Actions -->
        <div class="flex gap-3 mt-4">
            <a href="{{ \App\Helpers\Telegram::linkWithMessage() }}"
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