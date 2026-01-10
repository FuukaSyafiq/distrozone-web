<x-app-layout>
	{{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}
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
					@if($kaos->image && $kaos->image->count() > 0)
					<img id="mainImage" src="{{ Storage::url($kaos->image->first()->path) }}"
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
				@if($kaos->image && $kaos->image->count() > 1)
				<div class="grid grid-cols-5 gap-2">
					@foreach($kaos->image as $image)
					<button onclick="changeImage('{{ Storage::url($image->path) }}')"
						class="bg-gray-100 rounded-lg overflow-hidden aspect-square hover:ring-2 hover:ring-teal-500 transition-all">
						<img src="{{ Storage::url($image->path) }}" alt="{{ $kaos->nama_kaos }}"
							class="w-full h-full object-cover">
					</button>
					@endforeach
				</div>
				@endif
			</div>

			<!-- Middle Column - Product Details -->
			<div class="w-full lg:w-2/5 flex-shrink-0">
				<h1 class="text-2xl font-bold text-gray-900 mb-3">
					{{ $kaos->nama_kaos }}
				</h1>

				<!-- Price -->
				<div class="mb-6">
					<div class="flex items-baseline gap-3">
						<span class="text-3xl font-bold text-gray-900">
							Rp{{ number_format($kaos->harga_jual, 0, ',', '.') }}
						</span>
						@if(isset($kaos->harga_coret) && $kaos->harga_coret > $kaos->harga_jual)
						<span class="text-sm bg-red-100 text-red-600 px-2 py-1 rounded">
							{{ round((($kaos->harga_coret - $kaos->harga_jual) / $kaos->harga_coret) * 100) }}%
						</span>
					</div>
					<div class="text-sm text-gray-500 line-through mt-1">
						Rp{{ number_format($kaos->harga_coret, 0, ',', '.') }}
					</div>
					@endif
				</div>

				<!-- Color Selection -->
				<div class="mb-6">
					<h3 class="text-sm font-semibold text-gray-700 mb-3">Warna:</h3>
					<div class="flex items-center gap-3">
						<span class="w-8 h-8 rounded-full {{ $kaos->warna->tw_class }} border-2 border-gray-300"></span>
						<span class="text-sm font-semibold text-gray-800">
							{{ $kaos->warna->label }}
						</span>
					</div>
				</div>

				<!-- Size Selection -->
				<div class="mb-6">
					<h3 class="text-sm font-semibold text-gray-700 mb-3">Ukuran:</h3>
					<div class="flex flex-wrap gap-2">
						@php
						$sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
						@endphp

						@foreach($sizes as $size)
						<button
							class="px-4 py-2 border-2 rounded-lg font-medium transition-all
									 {{ strtoupper($kaos->ukuran) === $size ? 'border-teal-500 bg-teal-50 text-teal-700' : 'border-gray-200 hover:border-gray-300 text-gray-700' }}">
							{{ $size }}
						</button>
						@endforeach
					</div>
				</div>

				<!-- Product Info Tags -->
				<div class="flex flex-wrap gap-2">
					<span
						class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
						{{ ucfirst($kaos->type_kaos) }}
					</span>
					<span
						class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
						{{ $kaos->merek_kaos }}
					</span>
				</div>
			</div>

			<!-- Right Column - Purchase Box -->
			<livewire:purchase-box :kaos="$kaos"/>


		</div>
		<div>

	</div>

	<script>
		function changeImage(url) {
			document.getElementById('mainImage').src = url;
		}
	</script>
</x-app-layout>