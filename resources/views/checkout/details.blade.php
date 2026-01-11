<x-app-layout>
	@vite(['resources/css/app.css','resources/js/app.js'])

	<div class="py-8 max-w-6xl mx-auto px-4" x-data="{
        paymentMethod: 'qris',
        showQrisModal: false,
        showTransferModal: false,
        subtotal: 350000,
        ongkir: 15000,
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
            if (this.paymentMethod === 'qris') {
                this.showQrisModal = true;
            } else if (this.paymentMethod === 'transfer') {
                this.showTransferModal = true;
            }
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
						<a href="/profile" class="text-teal-600 hover:text-teal-700 text-sm font-medium mt-2">
							Edit Profile
						</a>
					</div>
				</div>

				<!-- Order Items -->
				<div class="bg-white rounded-xl shadow-md p-6">
					<h2 class="text-xl font-bold text-gray-900 mb-4">Pesanan Anda</h2>

					<!-- Sample Product 1 -->
					<div class="flex gap-4 pb-4 mb-4 border-b">
						<div class="w-20 h-20 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
							<img src="https://via.placeholder.com/80" alt="Product" class="w-full h-full object-cover">
						</div>
						<div class="flex-1">
							<h3 class="font-semibold text-gray-800 mb-1">Kaos Polos Premium</h3>
							<p class="text-sm text-gray-600 mb-2">Warna: Hitam, Size: L</p>
							<div class="flex justify-between items-center">
								<span class="text-sm text-gray-600">x2</span>
								<span class="font-semibold text-gray-900">Rp200.000</span>
							</div>
						</div>
					</div>

					<!-- Sample Product 2 -->
					<div class="flex gap-4">
						<div class="w-20 h-20 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
							<img src="https://via.placeholder.com/80" alt="Product" class="w-full h-full object-cover">
						</div>
						<div class="flex-1">
							<h3 class="font-semibold text-gray-800 mb-1">Kaos V-Neck Basic</h3>
							<p class="text-sm text-gray-600 mb-2">Warna: Putih, Size: M</p>
							<div class="flex justify-between items-center">
								<span class="text-sm text-gray-600">x1</span>
								<span class="font-semibold text-gray-900">Rp150.000</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Payment Method -->
				<div class="bg-white rounded-xl shadow-md p-6">
					<h2 class="text-xl font-bold text-gray-900 mb-4">Metode Pembayaran</h2>

					<div class="space-y-3">
						<!-- QRIS Option -->
						<label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all"
							:class="paymentMethod === 'qris' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
							<input type="radio" name="payment" value="qris" x-model="paymentMethod"
								class="w-5 h-5 text-teal-600 focus:ring-teal-500">
							<div class="ml-4 flex-1">
								<div class="flex items-center gap-2">
									<span class="font-semibold text-gray-900">QRIS</span>
									<span
										class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Instant</span>
								</div>
								<p class="text-sm text-gray-600 mt-1">Bayar dengan scan QR Code</p>
							</div>
							<div class="flex items-center gap-2">
								<svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
									<path
										d="M3 3h8v8H3V3zm2 2v4h4V5H5zm4 4H7V7h2v2zm-4 2h8v8H3v-8zm2 2v4h4v-4H5zm4 4H7v-2h2v2zM13 3h8v8h-8V3zm2 2v4h4V5h-4zm4 4h-2V7h2v2zm-4 2h8v8h-8v-8zm2 2v4h4v-4h-4zm4 4h-2v-2h2v2z" />
								</svg>
							</div>
						</label>

						<!-- Transfer Option -->
						<label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all"
							:class="paymentMethod === 'transfer' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
							<input type="radio" name="payment" value="transfer" x-model="paymentMethod"
								class="w-5 h-5 text-teal-600 focus:ring-teal-500">
							<div class="ml-4 flex-1">
								<span class="font-semibold text-gray-900">Transfer Bank</span>
								<p class="text-sm text-gray-600 mt-1">Transfer ke rekening bank</p>
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
							<span>Subtotal</span>
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

		<!-- QRIS Modal -->
		<div x-show="showQrisModal" x-cloak @click.self="showQrisModal = false"
			class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
			<div @click.away="showQrisModal = false"
				class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full p-8 relative">
				<!-- Close Button -->
				<button @click="showQrisModal = false"
					class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
					<!-- Left: QR Code -->
					<div class="flex flex-col items-center justify-center">
						<h3 class="text-2xl font-bold text-gray-900 mb-2">Scan QR Code</h3>
						<p class="text-gray-600 mb-6">Gunakan aplikasi e-wallet untuk scan</p>

						<!-- QRIS Image -->
						<div class="bg-white p-6 rounded-xl border-2 border-gray-200">
							<div class="bg-gray-100 w-64 h-64 rounded-lg flex items-center justify-center">
								<!-- Placeholder QR Code -->
								<svg class="w-full h-full" viewBox="0 0 100 100">
									<!-- Simple QR Code Pattern -->
									<rect width="100" height="100" fill="white" />
									<rect x="0" y="0" width="30" height="30" fill="black" />
									<rect x="70" y="0" width="30" height="30" fill="black" />
									<rect x="0" y="70" width="30" height="30" fill="black" />
									<rect x="5" y="5" width="20" height="20" fill="white" />
									<rect x="75" y="5" width="20" height="20" fill="white" />
									<rect x="5" y="75" width="20" height="20" fill="white" />
									<rect x="10" y="10" width="10" height="10" fill="black" />
									<rect x="80" y="10" width="10" height="10" fill="black" />
									<rect x="10" y="80" width="10" height="10" fill="black" />
									<!-- Random blocks -->
									<rect x="40" y="10" width="5" height="5" fill="black" />
									<rect x="50" y="10" width="5" height="5" fill="black" />
									<rect x="40" y="20" width="5" height="5" fill="black" />
									<rect x="60" y="20" width="5" height="5" fill="black" />
									<rect x="35" y="35" width="30" height="30" fill="black" />
									<rect x="40" y="40" width="20" height="20" fill="white" />
									<rect x="45" y="45" width="10" height="10" fill="black" />
								</svg>
							</div>
						</div>
					</div>

					<!-- Right: Payment Info -->
					<div class="flex flex-col justify-center">
						<div class="bg-teal-50 border border-teal-200 rounded-lg p-6 mb-6">
							<p class="text-sm text-teal-800 font-medium mb-2">Total Pembayaran</p>
							<p class="text-3xl font-bold text-teal-900" x-text="formatPrice(total)"></p>
						</div>

						<div class="bg-gray-50 rounded-lg p-4 mb-6">
							<h4 class="font-semibold text-gray-900 mb-3">Cara Pembayaran:</h4>
							<ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
								<li>Buka aplikasi e-wallet (GoPay, OVO, Dana, dll)</li>
								<li>Pilih menu Scan QR</li>
								<li>Arahkan kamera ke QR Code di layar</li>
								<li>Konfirmasi pembayaran di aplikasi</li>
							</ol>
						</div>

						<p class="text-sm text-gray-500 mb-6">
							Pembayaran akan otomatis terverifikasi setelah berhasil
						</p>

						<button @click="showQrisModal = false"
							class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors">
							Batal
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Transfer Modal -->
		<div x-show="showTransferModal" x-cloak @click.self="showTransferModal = false"
			class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
			<div @click.away="showTransferModal = false"
				class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full p-8 relative">
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
								<p class="text-lg font-bold text-gray-900">{{ \App\Helpers\Bank::NAME }}</p>
							</div>
							<div>
								<p class="text-sm text-gray-600 mb-1">Nomor Rekening</p>
								<div
									class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
									<p class="text-lg font-bold text-gray-900">{{ \App\Helpers\Bank::VIRTUAL_ACCOUNT }}</p>
									<button onclick="navigator.clipboard.writeText('{{ \App\Helpers\Bank::VIRTUAL_ACCOUNT }}')"
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

							<div class="bg-gray-50 rounded-lg p-4">
								<h4 class="font-semibold text-gray-900 mb-2 text-sm">Cara Transfer:</h4>
								<ol class="list-decimal list-inside space-y-1 text-sm text-gray-700">
									<li>Buka aplikasi mobile banking</li>
									<li>Pilih menu Transfer</li>
									<li>Masukkan nomor rekening tujuan</li>
									<li>Input nominal sesuai total pembayaran</li>
									<li>Konfirmasi transfer</li>
								</ol>
							</div>
						</div>

						<div class="space-y-3 mt-6">
							<button @click="showTransferModal = false; alert('Pembayaran berhasil dikonfirmasi!')"
								class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
								Selesai Bayar
							</button>

							<button @click="showTransferModal = false"
								class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors">
								Batalkan
							</button>
						</div>
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