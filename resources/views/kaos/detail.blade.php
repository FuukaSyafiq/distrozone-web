<x-app-layout>

	@php
	// use App\Models\KaosVariants;
	$selectedVariants = $kaos->variants[0];
	// dd($selectedVariants->warna_id);
	@endphp

	<div class="container mx-auto px-4 py-8">
		<!-- Breadcrumb -->
		<nav class="flex mb-6 text-sm">
			<a href="/" class="text-teal-600 hover:text-teal-700">Home</a>
			<span class="mx-2 text-gray-400">&gt;</span>
			<span class="text-gray-600">{{ Str::limit($kaos->nama_kaos, 30) }}</span>
		</nav>

		<div class="flex flex-col lg:flex-row gap-8 w-full">
			<!-- Left Column - Product Images -->
			<div class="w-full lg:w-1/4 flex-shrink-0">
				<!-- Main Image -->
				<div class="bg-gray-100 rounded-xl overflow-hidden mb-4 aspect-square">
					@if($selectedVariants)
					<img id="mainImage" src="{{ Storage::url($selectedVariants->image_path) }}"
						alt="{{ $kaos->nama_kaos }}" class="w-full h-full object-cover">
					@else
					<div class="w-full h-full flex items-center justify-center">
						<svg class="w-24 h-24 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd"
								d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
								clip-rule="evenodd" />
						</svg>
					</div>
					@endif
				</div>

				<!-- Thumbnail Images -->
				@if($kaos->variants)
				<div class="grid grid-cols-5 gap-2">
					@foreach($kaos->variants as $k)
					<button onclick="changeVariant({{ $k->id }}, '{{ Storage::url($k->image_path) }}')"
						class="bg-gray-100 rounded-lg overflow-hidden aspect-square hover:ring-2 hover:ring-teal-500 transition-all">
						<img src="{{ Storage::url($k->image_path) }}" alt="{{ $kaos->nama_kaos }}"
							class="w-full h-full object-cover">
					</button>
					@endforeach
				</div>
				@endif
			</div>

			<!-- Middle Column - Product Details -->
			<div class="w-full lg:w-2/5 flex-shrink-0">
				<h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $kaos->nama_kaos }}</h1>

				<!-- Price -->
				<div class="mb-6">
					<div class="flex items-baseline gap-3">
						<span class="text-3xl font-bold text-gray-900" id="productPrice">
							Rp{{ number_format($kaos->harga_jual, 0, ',', '.') }}
						</span>
					</div>
				</div>

				<!-- Color Selection -->
				<div class="mb-6">
					@if($selectedVariants && $selectedVariants->image_path)
					<h3 class="text-sm font-semibold text-gray-700 mb-3">Warna:</h3>
					<div class="flex items-center gap-3">
						@foreach ($kaos->variants as $k)
						<div class="flex flex-col justify-center items-center gap-2">
							<button onclick="changeVariant({{ $k->id }}, '{{ Storage::url($k->image_path) }}')"
								class="w-8 h-8 rounded-full {{ $k->warna->tw_class }} border-2 border-gray-300">
							</button>
							<span class="text-xs font-semibold text-gray-800">{{ $k->warna->label }}</span>
						</div>
						@endforeach
					</div>
					@endif
				</div>

				<!-- Size Selection -->
				<div class="mb-6">
					<h3 class="text-sm font-semibold text-gray-700 mb-3">Ukuran:</h3>
					<div class="flex flex-wrap gap-2" id="sizeContainer">
						@if($ukurans)
						@foreach($ukurans as $k)
							<button
								class="px-4 py-2 border-2 rounded-lg font-medium transition-all border-gray-200 hover:border-gray-300 text-gray-700">
								{{ $k->ukuran->ukuran }}
							</button>
						@endforeach
						@endif
					</div>
				</div>

				<!-- Product Info Tags -->
				<div class="flex flex-col gap-2">
					<div class="flex gap-4">
						<p>Tipe: </p>
						<span
							class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
							{{ ucfirst($kaos->type->type) }}
						</span>
					</div>
					<div class="flex gap-4">
						<p>Merek : </p>
						<span
							class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
							{{ ucfirst($kaos->merek->merek) }}
						</span>
					</div>
					<div class="mt-4">
						<p>{{ $kaos->description }}</p>
					</div>
				</div>
			</div>

			<!-- Right Column - Purchase Box -->
			<livewire:purchase-box :kaos="$kaos" :variantId="$selectedVariants->id"
				wire:key="purchaseBox-{{ $selectedVariants->id }}" id="purchaseBox" />

		</div>
	</div>
	<script>
		function changeImage(url) {
						document.getElementById('mainImage').src = url;
					}
				
					async function changeVariant(id, image) {
						// update gambar utama
						changeImage(image);
						const res = await fetch(`/variants/by-warna/${id}`);
						const sizes = await res.json();
				
						console.log(sizes)
						const container = document.getElementById('sizeContainer');
						container.innerHTML = '';
						
						sizes.forEach(size => {
							const btn = document.createElement('button');
							btn.className =
							'px-4 py-2 border-2 rounded-lg font-medium transition-all border-gray-200 hover:border-gray-300 text-gray-700';
							
							btn.textContent = size.ukuran;
							
							btn.onclick = () => {
							console.log('Selected size variant ID:', size.id);
							
							// emit ke Livewire (kalau perlu)
							if (window.Livewire) {
								Livewire.dispatch('variantChanged', { variantId: size.id });
							}
							};
							container.appendChild(btn);
						});
						// emit ke Livewire untuk update variant
						if (window.Livewire) {
							console.log('Emitting variantChanged event to Livewire'); // 🔹 Pastikan emit dijalankan
							Livewire.dispatch('variantChanged', { variantId: id });
						}
				
						console.log('Selected variant ID:', id);
					}
	</script>
</x-app-layout>