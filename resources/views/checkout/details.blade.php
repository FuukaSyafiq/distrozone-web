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
        paymentMethod: '{{ $paymentmethods->first()->nama_bank }}',
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
                this.showTransferModal = true;
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
						@foreach ($paymentmethods as $method)
						<label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all
							'border-gray-200 hover:border-gray-300">
							<input type="radio" name="payment" value="{{$method->nama_bank }}" x-model="paymentMethod"
							class="w-5 h-5 text-teal-600 focus:ring-teal-500">
							<div class="ml-4 flex-1">
								<span class="font-semibold text-gray-900">{{$method->nama_bank }}</span>
								<br />
								<p class="text-xs">{!! nl2br(e(str_replace('\n', "\n", $method->instruksi))) !!}</p>
							</div>
							<div class="flex items-center gap-2">
								<img src="{{ Storage::disk('s3')->url($method->logo) }}" width="300"
									alt="{{ $method->nama_bank }}" class="w-12 h-8 object-contain">
							</div>
						</label>
						@endforeach

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

						<form method="POST" action="{{ route('payment.confirm') }}">
							@csrf
							{{-- Data Pesanan --}}
							@foreach ($keranjang as $k)
							<input type="hidden" name="keranjang_details[]" value="{{ $k->id_keranjang_detail }}">
							@endforeach

							{{-- Hidden Inputs untuk Kalkulasi & Metode --}}
							<input type="hidden" name="payment_method" x-model="paymentMethod">
							<input type="hidden" name="total_amount" x-bind:value="total">
							<input type="hidden" name="ongkir_tarif_id" value="{{ $ongkirTarif->id }}">
							<input type="hidden" name="ongkir_total" value="{{ $ongkirValue }}">
							<input type="hidden" name="total" value="{{ $subtotal + $ongkirValue }}">

							<button type="submit"
								class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-lg shadow-teal-100">
								Checkout Sekarang
							</button>
						</form>

						<div class="mt-4 text-center text-sm text-gray-500">
							<p>Dengan melanjutkan, Anda menyetujui</p>
							<a href="#" class="text-teal-600 hover:text-teal-700 font-medium">Syarat & Ketentuan</a>
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