<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Pesanan Saya') }}
		</h2>
	</x-slot>

	<style>
		@keyframes blink {

			0%,
			50%,
			100% {
				opacity: 1;
			}

			25%,
			75% {
				opacity: 0.5;
			}
		}
	</style>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6">
					<!-- Tabs -->
					<div class="border-b border-gray-200 mb-6">
						<nav class="-mb-px flex space-x-8" aria-label="Tabs">
							<a href="{{ route('transaksi.render', ['status' => 'BELUMBAYAR']) }}"
								class="tab-link {{ request('status') === 'BELUMBAYAR' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
								Belum Bayar
								@if(isset($counts['BELUMBAYAR']) && $counts['BELUMBAYAR'] > 0)
								<span class="ml-2 bg-yellow-100 text-yellow-600 py-0.5 px-2.5 rounded-full text-xs">{{
									$counts['BELUMBAYAR'] }}</span>
								@endif
							</a>
							<a href="{{ route('transaksi.render', ['status' => 'PENDING']) }}"
								class="tab-link {{ (!request('status') || request('status') === 'PENDING') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
								Pending
								@if(isset($counts['PENDING']) && $counts['PENDING'] > 0)
								<span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs">{{
									$counts['PENDING'] }}</span>
								@endif
							</a>
							<a href="{{ route('transaksi.render', ['status' => 'DIKIRIM']) }}"
								class="tab-link {{ request('status') === 'DIKIRIM' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
								Dikirim
								@if(isset($counts['DIKIRIM']) && $counts['DIKIRIM'] > 0)
								<span class="ml-2 bg-purple-100 text-purple-600 py-0.5 px-2.5 rounded-full text-xs">{{
									$counts['DIKIRIM'] }}</span>
								@endif
							</a>
							<a href="{{ route('transaksi.render', ['status' => 'SUKSES']) }}"
								class="tab-link {{ request('status') === 'SUKSES' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
								Sukses
								@if(isset($counts['SUKSES']) && $counts['SUKSES'] > 0)
								<span class="ml-2 bg-green-100 text-green-600 py-0.5 px-2.5 rounded-full text-xs">{{
									$counts['SUKSES'] }}</span>
								@endif
							</a>
							<a href="{{ route('transaksi.render', ['status' => 'GAGAL']) }}"
								class="tab-link {{ request('status') === 'GAGAL' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
								Gagal
								@if(isset($counts['GAGAL']) && $counts['GAGAL'] > 0)
								<span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2.5 rounded-full text-xs">{{
									$counts['GAGAL'] }}</span>
								@endif
							</a>
						</nav>
					</div>

					<!-- Orders List -->
					@if($transaksis->isEmpty())
					<div class="text-center py-12">
						<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
							stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
						</svg>
						<h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pesanan</h3>
						<p class="mt-1 text-sm text-gray-500">Belum ada transaksi dengan status ini.</p>
					</div>
					@else
					<div class="space-y-4">
						@foreach($transaksis as $transaksi)
						<div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
							<!-- Header -->
							<div class="flex justify-between items-start mb-4">
								<div>
									<h3 class="font-semibold text-lg text-gray-900">{{ $transaksi->kode_transaksi }}
									</h3>
									<p class="text-sm text-gray-500">{{
										\Carbon\Carbon::parse($transaksi->created_at)->format('d M Y, H:i') }}</p>
								</div>
								<span class="status-badge 
                                            @if($transaksi->status === 'PENDING') bg-blue-100 text-blue-800
                                            @elseif($transaksi->status === 'BELUMBAYAR') bg-yellow-100 text-yellow-800
                                            @elseif($transaksi->status === 'DIKIRIM') bg-purple-100 text-purple-800
                                            @elseif($transaksi->status === 'SUKSES') bg-green-100 text-green-800
                                            @elseif($transaksi->status === 'GAGAL') bg-red-100 text-red-800
                                            @endif
                                            text-xs font-medium px-3 py-1 rounded-full">
									{{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}
								</span>
							</div>

							<!-- Timer untuk Belum Bayar -->
							@if($transaksi->status == \App\Helpers\TransaksiStatus::BELUMBAYAR &&
							$transaksi->expires_at)
							<div
								class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-lg">
								<div class="flex items-center">
									<svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor"
										viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>
									<div class="flex-1">
										<p class="text-sm font-medium text-yellow-900">Waktu Pembayaran Tersisa</p>
										<div class="countdown-timer text-2xl font-bold text-yellow-700 mt-1"
											data-expires="{{ $transaksi->expires_at }}"
											data-transaksi-id="{{ $transaksi->id_transaksi }}">
											<span class="hours">00</span>:<span class="minutes">00</span>:<span
												class="seconds">00</span>
										</div>
										<p class="text-xs text-yellow-700 mt-1">Selesaikan pembayaran sebelum <strong>{{
												\Carbon\Carbon::parse($transaksi->expires_at)->format('d M Y, H:i')
												}}</strong></p>
									</div>
								</div>
							</div>
							@endif

							@foreach ($transaksi->details as $k)

							<div class="flex gap-4 pb-4 mb-4 border-b last:border-b-0 last:pb-0 last:mb-0">
								<div class="w-20 h-20 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
									<img src="{{ Storage::disk('s3')->url($k->kaos_varian->image_path) }}"
										alt="{{ $k->kaos_varian->kaos->nama_kaos }}" class="w-full h-full object-cover">
								</div>
								<div class="flex-1">
									<h3 class="font-semibold text-gray-800 mb-1">{{ $k->kaos_varian->kaos->nama_kaos }}
									</h3>
									<p class="text-sm text-gray-600 mb-2">
										{{ $k->kaos_varian->warna->label }} - {{ $k->kaos_varian->ukuran->ukuran }}
									</p>
									<div class="flex justify-between items-center">
										<span class="text-sm text-gray-600">Quantity: {{ $k->qty }}</span>
										<span class="font-semibold text-gray-900">Rp{{ number_format($k->subtotal, 0,
											',', '.')
											}}</span>
									</div>
									<span class="text-xs text-gray-500">Rp. {{ number_format($k->harga_satuan, 0, ',',
										'.')
										}}</span>
								</div>
							</div>
							@endforeach

							<!-- Details -->
							<div class="grid grid-cols-3 gap-4 mb-4">
								<div>
									<p class="text-xs text-gray-500 mb-1">Jenis Transaksi</p>
									<p class="text-sm font-medium">{{ $transaksi->jenis_transaksi }}</p>
								</div>
								<div>
									<p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
									<p class="text-sm font-medium">{{ $transaksi->metode_pembayaran }}</p>
								</div>
								<div>
									<p class="text-xs text-gray-500 mb-1">Estimasi Sampai</p>
									<p class="text-sm font-medium">{{ \App\Helpers\Estimated::calculate(auth()->user()) }}</p>
								</div>
							</div>

							<!-- Price Info -->
							<div class="bg-gray-50 rounded-lg p-3 mb-4">
								<div class="flex justify-between text-sm mb-1">
									<span class="text-gray-600">Subtotal</span>
									<span class="font-medium">Rp {{ number_format($transaksi->total_harga -
										$transaksi->ongkir, 0, ',', '.') }}</span>
								</div>
								<div class="flex justify-between text-sm mb-2">
									<span class="text-gray-600">Ongkir</span>
									<span class="font-medium">Rp {{ number_format($transaksi->ongkir, 0, ',', '.')
										}}</span>
								</div>
								<div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200">
									<span>Total</span>
									<span class="text-blue-600">Rp {{ number_format($transaksi->total_harga, 0, ',',
										'.') }}</span>
								</div>
							</div>

							<!-- Actions -->
							<div class="flex justify-end space-x-2">
								@if ($transaksi->status == "DIKIRIM")
								<form action="{{ route('payment.bayar', $transaksi->id_transaksi) }}" method="POST">
									@csrf
									@method('PUT')

									<button
										class="inline-flex items-center px-4 py-2 bg-green-500 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
										Selesai
									</button>
								</form>
								@endif

								@if ($transaksi->status != \App\Helpers\TransaksiStatus::BELUMBAYAR && $transaksi->status != \App\Helpers\TransaksiStatus::GAGAL)
								<a href="{{ route('transaksi.cetak.pdf', $transaksi->id_transaksi) }}"
									class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
									Cetak
								</a>
								@endif

								@if ($transaksi->status == \App\Helpers\TransaksiStatus::BELUMBAYAR)
								<form action="{{ route('transaksi.tolak', $transaksi->id_transaksi) }}" method="POST">
									@csrf
									@method('PUT')

									<button
										class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
										Tolak
									</button>
								</form>
								<button
									onclick="openPaymentModal({{ $transaksi->id_transaksi }}, '{{ $transaksi->kode_transaksi }}', {{ $transaksi->total_harga }})"
									class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
									<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
									</svg>
									Bayar Sekarang
								</button>
								@endif
							</div>
						</div>
						@endforeach
					</div>

					<!-- Pagination -->
					@if($transaksis->hasPages())
					<div class="mt-6">
						{{ $transaksis->links() }}
					</div>
					@endif
					@endif
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Upload Bukti Pembayaran -->
	<div id="paymentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
		<div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">


			<!-- Modal Header -->
			<div class="flex justify-between items-center pb-3 border-b">
				<h3 class="text-xl font-semibold text-gray-900">Upload Bukti Pembayaran</h3>
				<button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 transition">
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>
			</div>

			<!-- Modal Body -->
			<form id="paymentForm" method="POST" enctype="multipart/form-data" class="mt-4">
				@csrf

				<!-- Informasi Transaksi -->
				<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
					<div class="flex items-start">
						<svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor"
							viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
						<div class="flex-1">
							<p class="text-sm font-medium text-blue-900">Detail Transaksi</p>
							<p class="text-xs text-blue-700 mt-1">Kode: <span id="modalKodeTransaksi"
									class="font-semibold"></span></p>
							<p class="text-xs text-blue-700">Total: <span id="modalTotalHarga"
									class="font-semibold"></span></p>
						</div>
					</div>
				</div>
				<livewire:payment-instruction :transaksiId="$transaksiId ?? null" wire:key="payment-instruction" />


				<!-- Upload File -->
				<div class="mb-4">
					<label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
						Bukti Pembayaran <span class="text-red-500">*</span>
					</label>
					<div
						class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
						<div class="space-y-1 text-center">
							<svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
								viewBox="0 0 48 48">
								<path
									d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
									stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
							<div class="flex text-sm text-gray-600">
								<label for="bukti_pembayaran"
									class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
									<span>Upload file</span>
									<input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="sr-only"
										accept="image/*,.pdf" required onchange="previewFile(this)">
								</label>
								<p class="pl-1">atau drag and drop</p>
							</div>
							<p class="text-xs text-gray-500">PNG, JPG, PDF hingga 5MB</p>
						</div>
					</div>

					<!-- Preview -->
					<div id="filePreview" class="hidden mt-3">
						<div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
							<div class="flex items-center">
								<svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor"
									viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
								</svg>
								<div>
									<p id="fileName" class="text-sm font-medium text-gray-900"></p>
									<p id="fileSize" class="text-xs text-gray-500"></p>
								</div>
							</div>
							<button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
								<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
								</svg>
							</button>
						</div>
					</div>
				</div>

				<!-- Catatan -->
				<div class="mb-4">
					<label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
						Catatan (Opsional)
					</label>
					<textarea id="catatan" name="catatan" rows="3"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
						placeholder="Tambahkan catatan jika diperlukan..."></textarea>
				</div>

				<!-- Modal Footer -->
				<div class="flex items-center justify-end space-x-3 pt-4 border-t">
					<button type="button" onclick="closePaymentModal()"
						class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
						Batal
					</button>
					<button type="submit"
						class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
						<svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
						</svg>
						Upload Bukti Pembayaran
					</button>
				</div>
			</form>
		</div>
	</div>

	<script>
		// Countdown Timer Function
		function startCountdown() {
			const timers = document.querySelectorAll('.countdown-timer');
			
			timers.forEach(timer => {
				const expiresAt = new Date(timer.dataset.expires).getTime();
				const transaksiId = timer.dataset.transaksiId;
				
				const updateTimer = () => {
					const now = new Date().getTime();
					const distance = expiresAt - now;
					
					if (distance < 0) {
						// Timer habis
						timer.innerHTML = '<span class="text-red-600 text-base font-semibold">Waktu Habis!</span>';
						timer.parentElement.parentElement.classList.remove('from-yellow-50', 'to-orange-50', 'border-yellow-400');
						timer.parentElement.parentElement.classList.add('from-red-50', 'to-red-50', 'border-red-400');
						
						// Disable tombol bayar
						const payButton = document.querySelector(`button[onclick*="${transaksiId}"]`);
						if (payButton) {
							payButton.disabled = true;
							payButton.classList.add('opacity-50', 'cursor-not-allowed');
							payButton.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Waktu Habis';
						}
						
						clearInterval(interval);
						return;
					}
					
					// Hitung waktu
					const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
					const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
					const seconds = Math.floor((distance % (1000 * 60)) / 1000);
					
					// Update tampilan
					timer.querySelector('.hours').textContent = String(hours).padStart(2, '0');
					timer.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
					timer.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
					
					// Ubah warna jika waktu kurang dari 1 jam
					if (distance < 3600000) { // 1 hour
						timer.classList.add('text-red-600');
						timer.classList.remove('text-yellow-700');
						
						// Animasi blink jika kurang dari 5 menit
						if (distance < 300000) { // 5 minutes
							timer.style.animation = 'blink 1s linear infinite';
						}
					}
				};
				
				// Update setiap detik
				updateTimer();
				const interval = setInterval(updateTimer, 1000);
			});
		}
		
		// Jalankan countdown saat halaman load
		document.addEventListener('DOMContentLoaded', startCountdown);

		function openPaymentModal(idTransaksi, kodeTransaksi, totalHarga) {
			document.getElementById('paymentModal').classList.remove('hidden');
			document.getElementById('modalKodeTransaksi').textContent = kodeTransaksi;
			document.getElementById('modalTotalHarga').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);
			Livewire.dispatch('set-transaksi-id', { id: idTransaksi });
			document.getElementById('paymentForm').action = `/bayar/${idTransaksi}`;
			document.body.style.overflow = 'hidden';
		}

		function closePaymentModal() {
			document.getElementById('paymentModal').classList.add('hidden');
			document.getElementById('paymentForm').reset();
			document.getElementById('filePreview').classList.add('hidden');
			document.body.style.overflow = 'auto';
		}

		function previewFile(input) {
			const file = input.files[0];
			if (file) {
				const fileSize = (file.size / 1024 / 1024).toFixed(2);
				
				// Validasi ukuran file (max 5MB)
				if (fileSize > 5) {
					alert('Ukuran file terlalu besar! Maksimal 5MB');
					input.value = '';
					return;
				}

				document.getElementById('fileName').textContent = file.name;
				document.getElementById('fileSize').textContent = fileSize + ' MB';
				document.getElementById('filePreview').classList.remove('hidden');
			}
		}

		function removeFile() {
			document.getElementById('bukti_pembayaran').value = '';
			document.getElementById('filePreview').classList.add('hidden');
		}

		// Close modal on escape key
		document.addEventListener('keydown', function(event) {
			if (event.key === 'Escape') {
				closePaymentModal();
			}
		});

		// Close modal on outside click
		document.getElementById('paymentModal').addEventListener('click', function(event) {
			if (event.target === this) {
				closePaymentModal();
			}
		});
	</script>
</x-app-layout>