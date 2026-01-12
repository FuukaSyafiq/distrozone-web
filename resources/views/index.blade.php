<x-app-layout>
    {{--
    <x-carousel /> --}}

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-xl font-bold text-gray-800 mb-8">Koleksi Kaos Kami</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($kaoss as $kaos)
            @php
            $variant = $kaos->variants;
            @endphp
            <div
                class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                <!-- Image Container -->
                <div class="relative bg-gray-100 aspect-square overflow-hidden group">
                    @if($variant && $variant[0]->image_path)
                    <img src="{{ Storage::disk('s3')->url($variant->image_path) }}" alt="{{ $kaos->nama_kaos }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                        <svg class="w-24 h-24 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endif


                </div>

                <!-- Card Content -->
                <div class="p-5 flex-1 flex flex-col">
                    <!-- Brand -->
                    <div class="text-sm text-gray-500 font-medium mb-1">
                        {{ $kaos->merek->merek }}
                    </div>

                    <!-- Product Name -->
                    <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 min-h-[3.5rem]">
                        {{ $kaos->nama_kaos }}
                    </h3>

                    <!-- Product Details -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($kaos->type->type) }}
                        </span>
                        @foreach ($variant as $w)

                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ ucfirst($w->warna->label) }}
                        </span>
                        @endforeach
                    </div>

                    <!-- Price -->
                    <div class="mt-auto">
                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($kaos->harga_jual, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>