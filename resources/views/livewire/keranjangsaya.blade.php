@php
// dd($keranjang_checkout);
@endphp

<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Keranjang saya</h2>
    </div>
    <div class="space-y-4">
        @if ($keranjang_aktif->isNotEmpty())
        <div class="flex flex-col">
            <h3 class="mb-3">{{ \App\Helpers\KeranjangStatus::AKTIF }}</h3>
            <label class="flex items-center gap-2 mb-4">
                <input type="checkbox" wire:model="selectAll">
                <span>Pilih Semua</span></label>
            <div class="flex flex-col gap-7">
                @foreach ($keranjang_aktif as $keranjang)
                <livewire:kaos-cart :keranjang="$keranjang" :wire:key="$keranjang->id"
                    wire:model="selected" />
                @endforeach
            </div>
        </div>
        @endif
        @if ($keranjang_checkout->isNotEmpty())
        <div class="flex flex-col">
            <h3 class="mb-3">{{ \App\Helpers\KeranjangStatus::CHECKOUT }}</h3>

             <livewire:kaos-cart />

        </div>
        @endif
        @if ($keranjang_dibatalkan->isNotEmpty())
        <div class="flex flex-col">
            <h3 class="mb-3">{{ \App\Helpers\KeranjangStatus::DIBATALKAN }}</h3>

             <livewire:kaos-cart />

        </div>
        @endif
    </div>
</div>