<x-app-layout>
	@php
	$subtotal = $keranjang->sum('subtotal');

	// ambil ongkir kota → fallback provinsi
	$ongkirTarif = \App\Models\Ongkir::where('wilayah', auth()->user()->kota->kota)->first();

	if (!$ongkirTarif) {
	$ongkirTarif = \App\Models\Ongkir::where(
	'wilayah',
	auth()->user()->kota->provinsi->provinsi
	)->first();
	}

	// safety check
	$ongkirValue = $ongkirTarif?->tarif_per_kg ?? 0;
	
	// total qty
	$totalQty = $keranjang->sum('qty');
	
	// hitung multiplier
	$multiplier = (int) ceil($totalQty / 3); // setiap 3 kaos = 1x ongkir
	
	// total ongkir
	$ongkirValue *= $multiplier;

	$total = $subtotal + $ongkirValue;
	@endphp

	<div class="py-8 max-w-6xl mx-auto px-4" x-data="{
        paymentMethod: 'bca',
        showQrisModal: false,
        showTransferModal: false,
        subtotal: {{ $subtotal }},
        ongkir: {{ $ongkirValue }},
        get total() {
            return this.subtotal + this.ongkir;
        },
        formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(price).replace('IDR', 'Rp');
        },
        processPayment() {
            if (this.paymentMethod === 'bca') {
                this.showTransferModal = true;
            } else if (this.paymentMethod === 'jago') {
                this.showTransferModal = true;
            }
        },
        uploadedFile: false,
        uploadedFileName: '',
        uploadedFileSize: '',
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB');
                    event.target.value = '';
                    return;
                }
                this.uploadedFile = true;
                this.uploadedFileName = file.name;
                this.uploadedFileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            }
        },
        removeFile() {
            this.uploadedFile = false;
            this.uploadedFileName = '';
            this.uploadedFileSize = '';
            document.getElementById('bukti-transfer').value = '';
        }
    }">
		<h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<!-- Left Section - Order Details & Payment Method -->
			<div class="lg:col-span-2 space-y-6">
				<!-- Shipping Address -->
				<div class="bg-white rounded-xl shadow-md p-6">
					<h2 class="text-xl font-bold text-gray-900 mb-4">Alamat Pengiriman</h2>
					<div class="space-y-2">
						<p class="font-semibold text-gray-800">{{ auth()->user()->nama }}</p>
						<p class="text-gray-600">{{ auth()->user()->no_telepon }}</p>
						<p class="text-gray-600">{{ auth()->user()->kota->kota }}</p>
						<p class="text-gray-600">{{ auth()->user()->kota->provinsi->provinsi }}</p>
						<p class="text-gray-600">{{ auth()->user()->alamat_lengkap }}</p>
						<a href="/profile"
							class="text-teal-600 hover:text-teal-700 text-sm font-medium mt-2 inline-block">
							Edit Profile
						</a>
					</div>
				</div>

				<!-- Order Items -->
				<div class="bg-white rounded-xl shadow-md p-6">
					<h2 class="text-xl font-bold text-gray-900 mb-4">Pesanan Anda</h2>

					@foreach ($keranjang as $k)
					<div class="flex gap-4 pb-4 mb-4 border-b last:border-b-0 last:pb-0 last:mb-0">
						<div class="w-20 h-20 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
							<img src="{{ Storage::disk('s3')->url($k->kaos_varian->image_path) }}"
								alt="{{ $k->kaos_varian->kaos->nama_kaos }}" class="w-full h-full object-cover">
						</div>
						<div class="flex-1">
							<h3 class="font-semibold text-gray-800 mb-1">{{ $k->kaos_varian->kaos->nama_kaos }}</h3>
							<p class="text-sm text-gray-600 mb-2">
								{{ $k->kaos_varian->warna->label }} - {{ $k->kaos_varian->ukuran->ukuran }}
							</p>
							<div class="flex justify-between items-center">
								<span class="text-sm text-gray-600">Quantity: {{ $k->qty }}</span>
								<span class="font-semibold text-gray-900">Rp{{ number_format($k->subtotal, 0, ',', '.')
									}}</span>
							</div>
							<span class="text-xs text-gray-500">Rp. {{ number_format($k->harga_satuan, 0, ',', '.')
								}}</span>
						</div>
					</div>
					@endforeach
				</div>

				<!-- Payment Method -->
				<div class="bg-white rounded-xl shadow-md p-6">
					<h2 class="text-xl font-bold text-gray-900 mb-4">Metode Pembayaran</h2>

					<div class="space-y-3">
						<!-- BANK BCA -->
						<label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all"
							:class="paymentMethod === 'bca' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
							<input type="radio" name="payment" value="bca" x-model="paymentMethod"
								class="w-5 h-5 text-teal-600 focus:ring-teal-500">
							<div class="ml-4 flex-1">
								<span class="font-semibold text-gray-900">BANK BCA</span>
								<p class="text-sm text-gray-600 mt-1">Transfer ke rekening bank BCA</p>
							</div>
							<div class="flex items-center gap-2">
								<svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor"
									viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
								</svg>
							</div>
						</label>

						<!-- BANK JAGO -->
						<label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all"
							:class="paymentMethod === 'jago' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
							<input type="radio" name="payment" value="jago" x-model="paymentMethod"
								class="w-5 h-5 text-teal-600 focus:ring-teal-500">
							<div class="ml-4 flex-1">
								<span class="font-semibold text-gray-900">BANK JAGO</span>
								<p class="text-sm text-gray-600 mt-1">Transfer ke rekening bank Jago</p>
							</div>
							<div class="flex items-center gap-2">
								<svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor"
									viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
								</svg>
							</div>
						</label>
					</div>
				</div>
			</div>

			<!-- Right Section - Order Summary -->
			<div class="lg:col-span-1">
				<div class="bg-white rounded-xl shadow-md p-6 sticky top-4">
					<h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>

					<div class="space-y-3 mb-4 pb-4 border-b">
						<div class="flex justify-between text-gray-700">
							<span>Subtotal ({{ $keranjang->sum('qty') }} item)</span>
							<span x-text="formatPrice(subtotal)"></span>
						</div>
						<div class="flex justify-between text-gray-700">
							<span>Ongkir</span>
							<span x-text="formatPrice(ongkir)"></span>
						</div>
					</div>

					<div class="flex justify-between items-center mb-6">
						<span class="text-lg font-bold text-gray-900">Total</span>
						<span class="text-2xl font-bold text-teal-600" x-text="formatPrice(total)"></span>
					</div>

					<button @click="processPayment"
						class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
						Bayar Sekarang
					</button>

					<div class="mt-4 text-center text-sm text-gray-500">
						<p>Dengan melanjutkan, Anda menyetujui</p>
						<a href="#" class="text-teal-600 hover:text-teal-700">Syarat & Ketentuan</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Transfer Modal -->
		<div x-show="showTransferModal" x-cloak @click.self="showTransferModal = false"
			class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
			<div @click.away="showTransferModal = false"
				class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full p-8 relative max-h-[90vh] overflow-y-auto">
				<!-- Close Button -->
				<button @click="showTransferModal = false"
					class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
					<!-- Left: Bank Details -->
					<div>
						<div class="flex items-center gap-3 mb-6">
							<div
								class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0">
								<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
									viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
								</svg>
							</div>
							<div>
								<h3 class="text-2xl font-bold text-gray-900">Transfer Bank</h3>
								<p class="text-gray-600">Transfer ke rekening di bawah ini</p>
							</div>
						</div>

						<!-- Bank Details -->
						<div class="bg-gray-50 rounded-xl p-6 space-y-4">
							<div>
								<p class="text-sm text-gray-600 mb-1">Nama Bank</p>
								<p class="text-lg font-bold text-gray-900"
									x-text="paymentMethod === 'bca' ? 'BCA' : 'BANK JAGO'"></p>
							</div>
							<div>
								<p class="text-sm text-gray-600 mb-1">Nomor Rekening</p>
								<div
									class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
									<p class="text-lg font-bold text-gray-900">{{ \App\Helpers\Bank::VIRTUAL_ACCOUNT }}
									</p>
									<button
										onclick="navigator.clipboard.writeText('{{ \App\Helpers\Bank::VIRTUAL_ACCOUNT }}')"
										class="text-teal-600 hover:text-teal-700 text-sm font-medium">
										Copy
									</button>
								</div>
							</div>
							<div>
								<p class="text-sm text-gray-600 mb-1">Atas Nama</p>
								<p class="text-lg font-bold text-gray-900">Distrozone</p>
							</div>
						</div>
					</div>

					<!-- Right: Payment Info & Action -->
					<div class="flex flex-col justify-between">
						<div>
							<div class="mb-6">
								<p class="text-sm text-gray-600 mb-2">Jumlah Transfer</p>
								<div class="bg-teal-50 border-2 border-teal-500 p-4 rounded-lg">
									<p class="text-3xl font-bold text-teal-600" x-text="formatPrice(total)"></p>
								</div>
							</div>

							<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
								<p class="text-sm text-yellow-800">
									<strong>Penting:</strong> Transfer sesuai nominal yang tertera agar pembayaran dapat
									diverifikasi otomatis.
								</p>
							</div>

							<div class="bg-gray-50 rounded-lg p-4 mb-6">
								<h4 class="font-semibold text-gray-900 mb-2 text-sm">Cara Transfer:</h4>
								<ol class="list-decimal list-inside space-y-1 text-sm text-gray-700">
									<li>Buka aplikasi mobile banking</li>
									<li>Pilih menu Transfer</li>
									<li>Masukkan nomor rekening tujuan</li>
									<li>Input nominal sesuai total pembayaran</li>
									<li>Konfirmasi transfer</li>
								</ol>
							</div>

							<!-- File Upload Section -->
							<div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6">
								<label for="bukti-transfer" class="block">
									<div class="text-center cursor-pointer">
										<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
											viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
												d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
										</svg>
										<p class="mt-2 text-sm text-gray-600">
											<span class="font-semibold text-teal-600">Upload bukti transfer</span>
										</p>
										<p class="text-xs text-gray-500 mt-1">PNG, JPG, PDF hingga 5MB</p>
									</div>
									{{-- <input id="bukti-transfer" name="bukti_transfer" type="file" class="hidden"
										accept="image/png,image/jpeg,image/jpg,application/pdf"
										@change="handleFileUpload($event)"> --}}
								</label>

								<!-- Preview uploaded file -->
								<div x-show="uploadedFile" class="mt-4 pt-4 border-t border-gray-200">
									<div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
										<div class="flex items-center gap-3">
											<svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor"
												viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
											</svg>
											<div>
												<p class="text-sm font-medium text-gray-900" x-text="uploadedFileName">
												</p>
												<p class="text-xs text-gray-500" x-text="uploadedFileSize"></p>
											</div>
										</div>
										<button @click="removeFile()" class="text-red-600 hover:text-red-700">
											<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
											</svg>
										</button>
									</div>
								</div>
							</div>
						</div>

						<form method="POST" action="{{ route('payment.confirm') }}" enctype="multipart/form-data"
							id="paymentForm">
							@csrf
							@foreach ($keranjang as $k)
							<input type="hidden" name="keranjang_details[]" value="{{ $k->id_keranjang_detail }}">

							@endforeach
							<input type="hidden" name="payment_method" x-model="paymentMethod">
							<input type="hidden" name="total_amount" x-bind:value="total">
							<input type="hidden" name="ongkir_tarif_id" value="{{ $ongkirTarif->id }}">
							<input type="hidden" name="ongkir_total" value="{{ $ongkirValue }}">
							<input type="hidden" name="total" value="{{ $subtotal + $ongkirValue }}">
							<input id="bukti-transfer" name="bukti_transfer" type="file" class="hidden"
								accept="image/png,image/jpeg,image/jpg,application/pdf"
								@change="handleFileUpload($event)">

							<div class="space-y-3 mt-6">
								<button type="submit" :disabled="!uploadedFile"
									:class="uploadedFile ? 'bg-teal-600 hover:bg-teal-700' : 'bg-gray-300 cursor-not-allowed'"
									class="w-full text-white font-bold py-3 px-4 rounded-lg transition-colors">
									Konfirmasi Pembayaran
								</button>

								<button type="button" @click="showTransferModal = false"
									class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors">
									Batalkan
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>


	</div>

	<style>
		[x-cloak] {
			display: none !important;
		}
	</style>
</x-app-layout>