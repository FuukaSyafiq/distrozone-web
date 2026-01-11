<x-app-layout>
	<div class="min-h-screen bg-gray-50 py-8">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<!-- Header -->
			<div class="mb-8">
				<h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
				<p class="mt-2 text-sm text-gray-600">{{ $cartItems->count() }} item dalam keranjang</p>
			</div>

			@if($cartItems->count() > 0)
			<div class="lg:grid lg:grid-cols-12 lg:gap-8">
				<!-- Cart Items -->
				<div class="lg:col-span-8">
					<div class="bg-white rounded-lg shadow">
						@foreach($cartItems as $item)
						<div class="p-6 border-b border-gray-200 last:border-b-0">
							<div class="flex gap-4">
								<!-- Product Image -->
								<div class="flex-shrink-0">
									<img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
										class="w-24 h-24 object-cover rounded-lg border border-gray-200">
								</div>

								<!-- Product Details -->
								<div class="flex-1 min-w-0">
									<div class="flex justify-between">
										<div>
											<h3 class="text-lg font-semibold text-gray-900">
												<a href="{{ route('cart.detail', $item->product->id) }}"
													class="hover:text-blue-600">
													{{ $item->product->name }}
												</a>
											</h3>
											@if($item->product->variant)
											<p class="mt-1 text-sm text-gray-500">{{ $item->product->variant }}</p>
											@endif
											<p class="mt-2 text-lg font-bold text-gray-900">
												Rp {{ number_format($item->product->price, 0, ',', '.') }}
											</p>
										</div>

										<!-- Remove Button -->
										<button type="button" onclick="confirmRemove({{ $item->id }})"
											class="text-gray-400 hover:text-red-500 transition-colors"
											aria-label="Hapus dari keranjang">
											<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
											</svg>
										</button>
									</div>

									<!-- Quantity Controls -->
									<div class="mt-4 flex items-center gap-4">
										<div class="flex items-center border border-gray-300 rounded-lg">
											<button type="button"
												onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
												class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-lg {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
												{{ $item->quantity <= 1 ? 'disabled' : '' }}>
													<svg class="w-4 h-4" fill="none" stroke="currentColor"
														viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round"
															stroke-width="2" d="M20 12H4" />
													</svg>
											</button>
											<input type="number" value="{{ $item->quantity }}" min="1"
												max="{{ $item->product->stock }}"
												class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none"
												readonly>
											<button type="button"
												onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
												class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-lg {{ $item->quantity >= $item->product->stock ? 'opacity-50 cursor-not-allowed' : '' }}"
												{{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}
												>
												<svg class="w-4 h-4" fill="none" stroke="currentColor"
													viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2" d="M12 4v16m8-8H4" />
												</svg>
											</button>
										</div>
										<span class="text-sm text-gray-500">Stok: {{ $item->product->stock }}</span>
									</div>

									<!-- Subtotal -->
									<div class="mt-3">
										<p class="text-sm text-gray-600">
											Subtotal:
											<span class="font-semibold text-gray-900">
												Rp {{ number_format($item->product->price * $item->quantity, 0, ',',
												'.') }}
											</span>
										</p>
									</div>
								</div>
							</div>
						</div>

						<!-- Hidden Remove Form -->
						<form id="remove-form-{{ $item->id }}" action="{{ route('cart.remove', $item->id) }}"
							method="POST" class="hidden">
							@csrf
							@method('DELETE')
						</form>

						<!-- Hidden Update Form -->
						<form id="update-form-{{ $item->id }}" action="{{ route('cart.update', $item->id) }}"
							method="POST" class="hidden">
							@csrf
							@method('PATCH')
							<input type="hidden" name="quantity" id="quantity-{{ $item->id }}">
						</form>
						@endforeach
					</div>
				</div>

				<!-- Order Summary -->
				<div class="mt-8 lg:mt-0 lg:col-span-4">
					<div class="bg-white rounded-lg shadow p-6 sticky top-8">
						<h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

						<div class="space-y-3 mb-4">
							<div class="flex justify-between text-gray-600">
								<span>Subtotal ({{ $cartItems->sum('quantity') }} item)</span>
								<span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
							</div>
							<div class="flex justify-between text-gray-600">
								<span>Biaya Pengiriman</span>
								<span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
							</div>
							@if($discount > 0)
							<div class="flex justify-between text-green-600">
								<span>Diskon</span>
								<span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
							</div>
							@endif
						</div>

						<div class="border-t border-gray-200 pt-4 mb-6">
							<div class="flex justify-between text-lg font-bold text-gray-900">
								<span>Total</span>
								<span>Rp {{ number_format($total, 0, ',', '.') }}</span>
							</div>
						</div>

						<!-- Promo Code -->
						<div class="mb-4">
							<form action="{{ route('cart.apply-promo') }}" method="POST">
								@csrf
								<div class="flex gap-2">
									<input type="text" name="promo_code" placeholder="Kode Promo"
										class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
									<button type="submit"
										class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
										Terapkan
									</button>
								</div>
							</form>
						</div>

						<!-- Checkout Button -->
						<a href="{{ route('checkout') }}"
							class="block w-full bg-blue-600 text-white text-center font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl">
							Lanjut ke Pembayaran
						</a>

						<a href="{{ route('products.index') }}"
							class="block w-full text-center text-blue-600 font-medium py-3 mt-3 hover:text-blue-700">
							Lanjut Belanja
						</a>
					</div>
				</div>
			</div>

			@else
			<!-- Empty Cart -->
			<div class="bg-white rounded-lg shadow p-12 text-center">
				<svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
						d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
				</svg>
				<h3 class="mt-6 text-xl font-semibold text-gray-900">Keranjang Belanja Kosong</h3>
				<p class="mt-2 text-gray-600">Belum ada produk yang ditambahkan ke keranjang</p>
				<a href="{{ route('products.index') }}"
					class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
					Mulai Belanja
				</a>
			</div>
			@endif
		</div>
	</div>

	<!-- Remove Confirmation Modal -->
	<div id="removeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
		<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
			<div class="mt-3 text-center">
				<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
					<svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
					</svg>
				</div>
				<h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Hapus dari Keranjang</h3>
				<div class="mt-2 px-7 py-3">
					<p class="text-sm text-gray-500">
						Apakah Anda yakin ingin menghapus produk ini dari keranjang?
					</p>
				</div>
				<div class="flex gap-3 px-4 py-3">
					<button onclick="closeRemoveModal()" type="button"
						class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-lg hover:bg-gray-300">
						Batal
					</button>
					<button onclick="submitRemove()" type="button"
						class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-lg hover:bg-red-700">
						Hapus
					</button>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
<script>
	let currentRemoveId = null;
		
		function confirmRemove(itemId) {
			currentRemoveId = itemId;
			document.getElementById('removeModal').classList.remove('hidden');
		}
		
		function closeRemoveModal() {
			document.getElementById('removeModal').classList.add('hidden');
			currentRemoveId = null;
		}
		
		function submitRemove() {
			if (currentRemoveId) {
				document.getElementById('remove-form-' + currentRemoveId).submit();
			}
		}
		
		function updateQuantity(itemId, newQuantity) {
			if (newQuantity < 1) return;
			
			document.getElementById('quantity-' + itemId).value = newQuantity;
			document.getElementById('update-form-' + itemId).submit();
		}
		
		// Close modal when clicking outside
		document.getElementById('removeModal')?.addEventListener('click', function(e) {
			if (e.target === this) {
				closeRemoveModal();
			}
		});
		
		// Close modal with ESC key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape') {
				closeRemoveModal();
			}
		});
</script>