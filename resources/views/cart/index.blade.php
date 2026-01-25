<x-app-layout>
	<div class="container mx-auto px-4 py-8">
		<!-- Page Title -->
		<div class="mb-8">
			<h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
			<p class="text-gray-600 mt-2">Kelola item yang ada di keranjang Anda</p>
		</div>
		@if(session('message'))
		<div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
			x-transition:enter-start="opacity-0 transform -translate-y-2"
			x-transition:enter-end="opacity-100 transform translate-y-0"
			class="mb-6 flex items-center p-4 text-orange-800 border-l-4 border-orange-500 bg-orange-50 rounded-r-xl shadow-sm"
			role="alert">

			<svg class="flex-shrink-0 w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
				<path fill-rule="evenodd"
					d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
					clip-rule="evenodd"></path>
			</svg>

			<div class="ml-3 text-sm font-bold tracking-wide uppercase">
				{{ session('message') }}
			</div>

			<button @click="show = false"
				class="ml-auto -mx-1.5 -my-1.5 bg-orange-50 text-orange-500 rounded-lg focus:ring-2 focus:ring-orange-400 p-1.5 hover:bg-orange-100 inline-flex h-8 w-8 transition-colors">
				<span class="sr-only">Close</span>
				<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd"
						d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
						clip-rule="evenodd"></path>
				</svg>
			</button>
		</div>
		@endif

		<!-- AKTIF Cart Table -->
		<div class="mb-8">
			<h2 class="text-xl font-bold text-gray-900 mb-4">Keranjang Aktif</h2>
			<div class="bg-white rounded-lg shadow overflow-hidden">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Produk</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Harga Satuan</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Qty</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Subtotal</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Tanggal</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Aksi</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@forelse($cartItems->where('keranjang.status', 'AKTIF') as $item)
						<tr class="hover:bg-gray-50">
							<td class="px-6 py-4">
								<div class="flex items-center">
									<img src="{{ Storage::url($item->kaos_varian->image_path) }}" alt="Product"
										class="w-16 h-16 rounded-lg object-cover">
									<div class="ml-4">
										<div class="text-sm font-medium text-gray-900">
											{{ $item->kaos_varian->kaos->nama_kaos ?? 'Kaos' }}
										</div>
										<div class="text-xs text-gray-500">
											{{ $item->kaos_varian->kaos->type->type }}
										</div>
										<div class="text-xs text-gray-500">
											{{ $item->kaos_varian->warna->label }} - {{
											$item->kaos_varian->ukuran->ukuran }}
										</div>
									</div>
								</div>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
								Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
								{{ $item->qty }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
								Rp{{ number_format($item->subtotal, 0, ',', '.') }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
								{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y') }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm">
								<form action="{{ route('cart-delete', $item->id_keranjang_detail) }}" method="POST"
									onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
									@csrf

									<input type="hidden" name="quantity" value="{{ $item->qty }}">
									@method('DELETE')
									<button type="submit" class="text-red-600 hover:text-red-900 font-medium">
										Hapus
									</button>
								</form>
								<a href="/kaos/{{ $item->kaos_varian->kaos->id_kaos }}" class="text-green-600 hover:text-green-900 font-medium">
									Lihat
								</a>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="5" class="px-6 py-8 text-center text-gray-500">
								Tidak ada item di keranjang aktif
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			<!-- Cart Summary for AKTIF -->
			@if($cartItems->where('keranjang.status', 'AKTIF')->count() > 0)
			<div class="mt-6 flex justify-end">
				<div class="bg-white rounded-lg shadow p-6 w-full md:w-96">
					<h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h3>

					<div class="space-y-3">
						<div class="flex justify-between text-sm">
							<span class="text-gray-600">Total Item</span>
							<span class="font-medium">
								{{ $cartItems->where('keranjang.status', 'AKTIF')->count() }}
							</span>
						</div>
						<div class="flex justify-between text-sm">
							<span class="text-gray-600">Total Belanja</span>
							<span class="font-semibold text-lg text-gray-900">
								Rp{{ number_format($cartItems->where('keranjang.status', 'AKTIF')->sum('subtotal'), 0,
								',', '.') }}
							</span>
						</div>
					</div>

					@php
					$activeItems = $cartItems->where('keranjang.status', 'AKTIF');
					@endphp

					<form action="{{ route('cart.check') }}" method="POST" class="mt-6">
						@csrf

						@forelse($activeItems as $item)
						<input type="hidden" name="keranjang_detail_ids[]" value="{{ $item->id_keranjang_detail }}">
						@empty
						@endforelse

						<button type="submit"
							class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-lg transition-colors">
							Checkout
						</button>
					</form>
				</div>
			</div>
			@endif
		</div>

		<!-- CHECKOUT Cart Table -->
		<div class="mb-8">
			<h2 class="text-xl font-bold text-gray-900 mb-4">Sedang Dipesan</h2>
			<div class="bg-white rounded-lg shadow overflow-hidden">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Produk</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Harga Satuan</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Qty</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Subtotal</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Tanggal</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Lihat Kaos</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@forelse($cartItems->where('keranjang.status', 'CHECKOUT') as $item)
						<tr class="hover:bg-gray-50">
							<td class="px-6 py-4">
								<div class="flex items-center">
									<img src="{{ Storage::url($item->kaos_varian->image_path) }}" alt="Product"
										class="w-16 h-16 rounded-lg object-cover">
									<div class="ml-4">
										<div class="text-sm font-medium text-gray-900">
											{{ $item->kaos_varian->kaos->nama_kaos ?? 'Kaos' }}
										</div>
										<div class="text-xs text-gray-500">
											{{ $item->kaos_varian->kaos->type->type }}
										</div>
										<div class="text-xs text-gray-500">
											{{ $item->kaos_varian->warna->label }} - {{
											$item->kaos_varian->ukuran->ukuran }}
										</div>
									</div>
								</div>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
								Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
								{{ $item->qty }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
								Rp{{ number_format($item->subtotal, 0, ',', '.') }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<span
									class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
									{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y') }}
								</span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<a href="/kaos/{{ $item->kaos_varian->kaos->id_kaos }}"
									class="text-green-600 hover:text-green-900 font-medium">
									Lihat
								</a>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="5" class="px-6 py-8 text-center text-gray-500">
								Tidak ada item yang sedang diproses
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- DIBATALKAN Cart Table -->
		<div class="mb-8">
			<h2 class="text-xl font-bold text-gray-900 mb-4">Keranjang Dibatalkan</h2>
			<div class="bg-white rounded-lg shadow overflow-hidden">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Produk</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Harga Satuan</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Qty</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Subtotal</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Aksi</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@forelse($cartItems->where('keranjang.status', 'DIBATALKAN') as $item)
						<tr class="hover:bg-gray-50">
							<td class="px-6 py-4">
								<div class="flex items-center">
									<img src="{{ Storage::url($item->kaos_varian->image_path) }}" alt="Product"
										class="w-16 h-16 rounded-lg object-cover">
									<div class="ml-4">
										<div class="text-sm font-medium text-gray-900">
											{{ $item->kaos_varian->kaos->nama_kaos ?? 'Kaos' }}
										</div>
										<div class="text-xs text-gray-500">
											{{ $item->kaos_varian->kaos->type->type }}
										</div>
										<div class="text-xs text-gray-500">
											{{ $item->kaos_varian->warna->label }} - {{
											$item->kaos_varian->ukuran->ukuran }}
										</div>
									</div>
								</div>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
								Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
								{{ $item->qty }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
								Rp{{ number_format($item->subtotal, 0, ',', '.') }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<span
									class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
									DIBATALKAN
								</span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm">
								<form action="{{ route('cart-delete', $item->id_keranjang_detail) }}" method="POST"
									onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
									@csrf
									@method('DELETE')
									<button type="submit" class="text-red-600 hover:text-red-900 font-medium">
										Hapus
									</button>
								</form>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="6" class="px-6 py-8 text-center text-gray-500">
								Tidak ada item yang dibatalkan
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</x-app-layout>