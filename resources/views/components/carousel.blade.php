<section class="relative w-full">
	<div class="relative overflow-hidden rounded-lg h-24 sm:h-36 md:h-60 lg:h-80">

		<!-- Slide 1 -->
		<div class="absolute inset-0 transition-opacity duration-700 opacity-100" id="slide1">
			<img src="{{ asset('assets/banner/banner1.png') }}" class="w-full h-full object-cover object-center"
				alt="Banner 1">
		</div>

		<!-- Slide 2 -->
		<div class="absolute inset-0 transition-opacity duration-700 opacity-0" id="slide2">
			<img src="{{ asset('assets/banner/banner2.png') }}" class="w-full h-full object-cover object-center"
				alt="Banner 2">
		</div>

		<!-- Slide 3 -->
		<div class="absolute inset-0 transition-opacity duration-700 opacity-0" id="slide3">
			<img src="{{ asset('assets/banner/banner3.png') }}" class="w-full h-full object-cover object-center"
				alt="Banner 3">
		</div>

		<!-- Slide 4 -->
		<div class="absolute inset-0 transition-opacity duration-700 opacity-0" id="slide4">
			<img src="{{ asset('assets/banner/banner4.png') }}" class="w-full h-full object-cover object-center"
				alt="Banner 4">
		</div>
	</div>

	<!-- Indicators -->
	<div class="absolute z-30 flex -translate-x-1/2 bottom-4 left-1/2 space-x-2">
		<button class="w-3 h-3 bg-white rounded-full" onclick="showSlide(1)"></button>
		<button class="w-3 h-3 bg-white/50 rounded-full" onclick="showSlide(2)"></button>
		<button class="w-3 h-3 bg-white/50 rounded-full" onclick="showSlide(3)"></button>
		<button class="w-3 h-3 bg-white/50 rounded-full" onclick="showSlide(4)"></button>
	</div>

	<!-- Controls -->
	<button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4"
		onclick="prevSlide()">
		<span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 hover:bg-white/50">
			<svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 6 10">
				<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
					d="M5 1 1 5l4 4" />
			</svg>
		</span>
	</button>
	<button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4"
		onclick="nextSlide()">
		<span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 hover:bg-white/50">
			<svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 6 10">
				<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
					d="m1 9 4-4-4-4" />
			</svg>
		</span>
	</button>
</section>
  