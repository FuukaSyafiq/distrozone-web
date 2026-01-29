<x-app-layout>
	@php
	$subtotal = $keranjang->sum('subtotal');

	$user = auth()->user();

	$ongkirValue = 0;
	$multiplier = 0;

	// Hanya hitung ongkir jika kota_id DAN alamat_lengkap terisi
	if ($user->kota_id && $user->alamat_lengkap) {

	// 1. Cek ongkir berdasarkan Nama Kota
	$ongkirTarif = \App\Models\Ongkir::where('wilayah', $user->kota->kota)->first();

	// 2. Jika kota tidak ketemu, fallback ke Provinsi
	if (!$ongkirTarif && $user->kota->provinsi) {
	$ongkirTarif = \App\Models\Ongkir::where('wilayah', $user->kota->provinsi->provinsi)->first();
	}

	// Ambil nilai tarif per kg
	$tarifDasar = $ongkirTarif?->tarif_per_kg ?? 0;

	// Hitung Multiplier (setiap 3 kaos = 1kg/1x ongkir)
	$totalQty = $keranjang->sum('qty');
	$multiplier = (int) ceil($totalQty / 3);

	$ongkirValue = $tarifDasar * $multiplier;
	}

	$total = $subtotal + $ongkirValue;
	@endphp

	<div class="py-8 max-w-6xl mx-auto px-4" x-data="{
        paymentMethod: '{{ $paymentmethods->first()->nama_bank }}',
        showTransferModal: false,
        subtotal: {{ $subtotal }},
        ongkir: {{ $ongkirValue  }},
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


		<div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
			<!-- Left Section - Order Details & Payment Method -->
			<div class="lg:col-span-2 space-y-6">
				<!-- Shipping Address -->
				<div class="bg-white rounded-xl shadow-md p-6">
					<h2 class="text-xl font-bold text-gray-900 mb-4">Alamat Pengiriman</h2>
					<div class="space-y-2">
						<p class="font-semibold text-gray-800">{{ auth()->user()->nama }}</p>
						<p class="text-gray-600">{{ auth()->user()->no_telepon }}</p>
						<p class="text-gray-600">Kota : {{ auth()->user()->kota->kota ?? "" }}</p>
						<p class="text-gray-600">Provinsi : {{ auth()->user()->kota->provinsi->provinsi ?? "" }}</p>
						<p class="text-gray-600">Alamat lengkap : {{ auth()->user()->alamat_lengkap ?? "" }}</p>
						<a href="/profile"
							class="text-teal-600 hover:text-teal-700 text-sm font-medium mt-2 inline-block">
							Edit Profile
						</a>
					</div>
				</div>
				@if(auth()->user()->kota_id == null || auth()->user()->alamat_lengkap == null)
				<div
					class="w-full mb-6 overflow-hidden rounded-2xl bg-red-500/10 border border-red-500/50 backdrop-blur-md">
					<div class="p-5 flex flex-col md:flex-row items-center justify-between gap-4">
						<div class="flex items-center gap-4 text-center md:text-left">
							<div class="p-3 bg-red-500 rounded-xl text-white shadow-lg shadow-red-500/20">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
									stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
								</svg>
							</div>
							<div>
								<h4 class="text-white font-bold uppercase tracking-wider">Alamat Belum Lengkap!</h4>
								<p class="text-gray-400 text-sm">Ongkir tidak dapat dihitung. Silakan isi Kota dan
									Alamat Lengkap untuk
									melanjutkan checkout.</p>
							</div>
						</div>

						<a href="{{ route('profile') }}"
							class="whitespace-nowrap px-6 py-3 bg-orange-600 hover:bg-orange-500 text-white font-bold rounded-xl transition-all hover:scale-105 shadow-lg shadow-orange-600/20 uppercase text-xs tracking-widest">
							Lengkapi Alamat
						</a>
					</div>
				</div>
				@endif

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
						<div class="mb-6">
							<p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Estimasi Tiba</p>
							<p class="text-sm font-semibold text-gray-800">{{ \App\Helpers\Estimated::calculate(auth()->user()) }}</p>
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
							<input type="hidden" name="ongkir_tarif_id" value="{{ $ongkirTarif->id ?? null }}">
							<input type="hidden" name="ongkir_total" value="{{ $ongkirValue }}">
							<input type="hidden" name="total" value="{{ $subtotal + $ongkirValue }}">

							<button type="submit" @disabled($ongkirValue==0) class="w-full font-black uppercase tracking-widest py-4 px-6 rounded-xl transition-all duration-300 shadow-xl
							    {{ $ongkirValue == 0 
							        ? 'bg-gray-700 text-gray-400 cursor-not-allowed opacity-50' 
							        : 'bg-gradient-to-r from-teal-500 to-teal-700 text-white hover:scale-[1.02] hover:shadow-teal-500/40 active:scale-95' 
							    }}">

								<div class="flex items-center justify-center gap-2">
									@if($ongkirValue == 0)
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
										fill="currentColor">
										<path fill-rule="evenodd"
											d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.366zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367z"
											clip-rule="evenodd" />
									</svg>
									<span>Alamat Belum Lengkap</span>
									@else
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
										fill="currentColor">
										<path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
										<path fill-rule="evenodd"
											d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
											clip-rule="evenodd" />
									</svg>
									<span>Checkout Sekarang</span>
									@endif
								</div>
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
        </div> </div> </x-app-layout>