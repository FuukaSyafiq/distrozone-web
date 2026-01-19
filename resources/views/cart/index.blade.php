<x-app-layout>
	<div class="container mx-auto px-4 py-8">
		<!-- Page Title -->
		<div class="mb-8">
			<h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
			<p class="text-gray-600 mt-2">Kelola item yang ada di keranjang Anda</p>
		</div>

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
								Rp{{ number_format($cartItems->where('keranjang.status', 'AKTIF')->sum('subtotal'), 0, ',', '.') }}
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