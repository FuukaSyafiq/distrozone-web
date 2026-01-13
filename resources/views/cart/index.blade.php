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
								<input type="checkbox" id="select-all-aktif"
									class="w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
							</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Produk</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Harga
								Satuan</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Qty
							</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Subtotal</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Aksi
							</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@forelse($cartItems->where('keranjang.status', 'AKTIF') as $item)
						<tr class="hover:bg-gray-50">
							<td class="px-6 py-4 whitespace-nowrap">
								<input type="checkbox"
									class="item-checkbox-aktif w-4 h-4 text-teal-600 rounded focus:ring-teal-500"
									data-id="{{ $item->id_keranjang_detail }}">
							</td>
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
							<td class="px-6 py-4 whitespace-nowrap">
								<div class="flex items-center gap-2">
									<button onclick="updateQty({{ $item->id_keranjang_detail }}, -1)"
										class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">-</button>
									<span class="w-8 text-center text-sm font-medium"
										id="qty-{{ $item->id_keranjang_detail }}">
										{{ $item->qty }}
									</span>
									<button onclick="updateQty({{ $item->id_keranjang_detail }}, 1)"
										class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">+</button>
								</div>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
								Rp{{ number_format($item->subtotal, 0, ',', '.') }}
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
							<span class="font-medium">{{ $cartItems->where('keranjang.status', 'AKTIF')->count()
								}}</span>
						</div>
						<div class="flex justify-between text-sm">
							<span class="text-gray-600">Total Belanja</span>
							<span class="font-semibold text-lg text-gray-900">
								Rp{{ number_format($cartItems->where('keranjang.status', 'AKTIF')->sum('subtotal'), 0,
								',', '.') }}
							</span>
						</div>
					</div>
				<a href="{{ route('checkout') }}?keranjang[]=1"
						class="w-full mt-6 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-lg transition-colors text-center block">
						Checkout
					</a>				 
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
								Harga
								Satuan</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Qty
							</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Subtotal</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Status</th>
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
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
								{{ $item->qty }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
								Rp{{ number_format($item->subtotal, 0, ',', '.') }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<span
									class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
									CHECKOUT
								</span>
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
								Harga
								Satuan</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Qty
							</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Subtotal</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Aksi
							</th>
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
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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

	<script>
		// Select all functionality for AKTIF items
		document.getElementById('select-all-aktif')?.addEventListener('change', function() {
			const checkboxes = document.querySelectorAll('.item-checkbox-aktif');
			checkboxes.forEach(checkbox => {
				checkbox.checked = this.checked;
			});
		});

		// Update quantity
		function updateQty(itemId, change) {
			const qtyElement = document.getElementById(`qty-${itemId}`);
			let currentQty = parseInt(qtyElement.textContent);
			let newQty = currentQty + change;
			
			if (newQty < 1) newQty = 1;
			
			qtyElement.textContent = newQty;
			
			// TODO: Send AJAX request to update quantity in database
			console.log(`Update item ${itemId} to qty ${newQty}`);
		}

		function redirectCheckout() {

			window.href = "/checkout?kerajang=1";

		}
	</script>
</x-app-layout>