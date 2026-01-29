<div class="my-4">
    <div wire:loading class="w-full text-center py-4">
        <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-blue-600 rounded-full"
            role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="text-xs text-gray-500 mt-2">Mengambil instruksi pembayaran...</p>
    </div>

    <div wire:loading.remove>
        @if($method)
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div
                class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-2xl border border-gray-100 mb-4 transition-all hover:border-teal-500/50 group">
                <div class="w-full h-40 mb-4 flex items-center justify-center overflow-hidden">
                    <img src="{{ Storage::disk('s3')->url($method->logo) }}"
                        class="h-full w-full object-contain transform group-hover:scale-110 transition-transform duration-300"
                        alt="Logo {{ $method->nama_bank }}">
                </div>

                <span class="font-black text-xl text-gray-900 uppercase tracking-tighter italic">
                    {{ $method->nama_bank }}
                </span>
            </div>

            <div class="bg-white p-3 rounded-lg border border-dashed border-gray-300 mb-4">
                <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Nomor Rekening / VA</p>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-mono font-bold text-blue-700 tracking-wider">{{ $method->nomor_rekening
                        }}</span>
                    <button type="button"
                        onclick="navigator.clipboard.writeText('{{ $method->nomor_rekening }}'); alert('Nomor rekening disalin!')"
                        class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded font-bold hover:bg-blue-200 transition">
                        SALIN
                    </button>
                </div>
                <p class="text-sm text-gray-600 font-medium mt-1">A/N {{ $method->nama_penerima }}</p>
            </div>

            <div>
                <p class="text-[10px] uppercase font-bold text-gray-400 mb-2">Langkah Pembayaran</p>
                <div class="text-xs text-gray-600 leading-relaxed space-y-1">
                    {!! nl2br(e(str_replace('\n', "\n", $method->instruksi))) !!}
                </div>
            </div>
        </div>
        @else
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-xs text-yellow-700">
            Pilih metode pembayaran untuk melihat instruksi.
        </div>
        @endif
    </div>
</div>