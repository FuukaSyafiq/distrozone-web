{{-- <x-header :user="$user" /> --}}
<x-app-layout>
    <x-carousel />

    <!-- Popular Products -->
    <section
        class="flex w-full py-1 md:py-4 lg:py-7 justify-center items-center flex-wrap my-10 gap-10 text-center md:gap-3 mx-auto">
        {{-- @foreach ($popular as $pop)
            <a href="product/{{$pop->id}}" style="text-decoration: none" class="text-black px-3  no-underline">
                <div class="shadow-md p-2 m-auto flex flex-col md:m-3 rounded-md cursor-pointer justify-content-start">
                    <div
                        class="md:w-[120px] w-[70px] h-[70px] md:h-[120px] m-auto flex overflow-hidden items-center justify-center">
                        <img src="{{$pop->image_path}}" class="rounded-md object-cover w-full h-full object-center" />
                    </div>
                    <div class="flex justify-center mx-auto w-full  flex-wrap">
                        <p class="pt-1 text-xs font-bold text-center md:text-md">{{shortTitle($pop->title)}}</p>
                    </div>
                </div>
            </a>
        @endforeach --}}
    </section>
    <!-- popular Products end -->

    <!-- Promo -->
    <section class="flex w-4/5 mx-auto justify-between lg:flex-row flex-col  shadow-lg rounded-xl">
        <div class="lg:w-1/5 w-full flex-col flex justify-center rounded-md px-4 bg-black text-white">
            <h2 class="font-bold lg:text-lg text-md md:mb-3">Promo hari ini</h2>
            <p class="font-bold lg:text-2xl text-md md:block hidden text-left lg:text-center mb-3">
                Hingga 40%</p>
            <p class="text-sm md:block hidden">Dapatkan produk eksklusif kami dengan diskon spesial hanya untuk hari
                ini!</p>
        </div>
        <div class="flex gap-10 lg:w-4/5 justify-center items-center border-black flex-wrap m-auto lg:px-0 border-opacity-50 p-4 rounded-lg"
            id="promoProduct">
            {{-- @foreach ($promo as $p)
                    <a href="product/{{$p->id}}" class="text-black" style="text-decoration: none">
                        <div class="shadow-sm flex flex-col p-2 rounded-xl">
                            <div class="md:w-[100px] w-auto mx-auto flex flex-col overflow-hidden h-[100px]">
                                <img src="
                  {{$p->image_path}}
                  " class="w-full h-full rounded-md object-center object-cover" />
                            </div>
                            <div class="flex flex-col w-full h-full flex-wrap">
                                <div class="flex flex-col items-center">
                                    <p class="md:font-bold font-medium text-sm">
                                        {{shortTitle($p->title)}}
                                    </p>
                                    <p class="leading-5 font-bold text-sm">
                                        {{Illuminate\Support\Number::currency($p->price, 'IDR', 'de')}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
            @endforeach --}}
        </div>
    </section>
    <!-- Promo end-->

    <!-- Big card product -->
    <section class="flex lg:w-4/5 justify-center items-center flex-wrap my-10 lg:gap-10 md:gap-5 gap-4 mx-auto">
        {{-- @foreach ($other as $p)
            <a href="product/{{$p->id}}" class="" style="text-decoration: none">
                <div class="shadow-md p-3 flex flex-col m-auto rounded-md cursor-pointer">
                    <!-- Image -->
                    <div
                        class="flex flex-col overflow-hidden m-auto rounded-md justify-center items-center md:h-[120px] md:w-[120px] sm:w-[70px] sm:h-[70px] h-[70px] w-[70px]">
                        <img src="{{$p->image_path}}" class="rounded-md w-full h-full object-cover object-center mx-auto" />
                    </div>

                    <!-- Title, Stock, Rating,Location, Price -->
                    <div class="flex flex-col md:w-48 sm:w-32 w-24 flex-wrap mt-3">
                        <div class="flex flex-col flex-wrap">
                            <h2 class="font-bold text-wrap md:text-xl sm:text-md text-sm overflow-hidden">{{
                                        shortTitle($p->title)
                                }}</h2>
                            <p class="font-bold md:text-lg sm:text-xs text-sm">
                                {{Illuminate\Support\Number::currency($p->price, 'IDR', 'de')}}
                            </p>
                        </div>
                        <p class="text-sm md:text-md">Stock: {{$p->stock}}</p>
                        <div class="flex items-center">
                            <div class="flex gap-1 items-center ">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $p->rating)
                                        <x-bi-star-fill class="text-yellow-500" />
                                    @else
                                        <x-bi-star class="text-gray-400" />
                                    @endif
                                @endfor
                            </div>
                            <p class="ml-2 font-bold md:text-md sm:text-sm text-xs">{{
                $p->rating
            }}</p>
                        </div>
                        <div class="flex items-center">
                            <div class="flex gap-1 items-center">
                                <x-fas-home class="text-black" /> {{-- lebih kecil --}}
                            {{-- </div> --}}
                            {{-- <p class="font-bold md:text-md sm:text-sm text-xs">
                                {{ $p->location }}
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach --}} 
    </section>
    <!-- Big card product-->

</x-app-layout>

