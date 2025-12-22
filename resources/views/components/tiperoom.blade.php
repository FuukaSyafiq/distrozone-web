@php
    use App\Models\Review;
    // $averageStar = Review::getAverageStarForRoom($data->id);
@endphp

@props([
    'data' => [
        'id' => '',
        'tipe' => '',
        'price' => '',
        'facility' => '',
        'ukuran' => '',
        'path' => '',
    ],
])


<div class="m-2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <a href="/tiperoom/{{ $data->id }}">
        <img class="p-4 rounded-t-lg object-cover w-full h-72" src="{{ $data->image_path}}" alt="product image" />
        <div class="p-5 flex flex-col h-40">
            <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white flex-1">{{ $data->tipe }}
            </h5>
            <div class="flex items-center mt-2.5 mb-2">
                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                    {{-- @for ($i = 1; $i <= $averageStar->avg_star; $i++)
                        <x-star />
                    @endfor --}}
                </div>
                {{-- <span
                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-3">
                    {{ number_format($averageStar->avg_star, 1)  }}</span> --}}
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xl font-bold text-gray-900 dark:text-white price" data-price="{{ $data->price }}">
                    {{ "Rp. " .number_format($data->price, 0, ',', '.') }}</span>
            </div>
        </div>
    </a>
</div>
