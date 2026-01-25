<x-app-layout>
	<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
		<div class="max-w-md w-full space-y-8">
			<div>
				<h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
					Verifikasi OTP
				</h2>
				<p class="mt-2 text-center text-sm text-gray-600">
					Masukkan kode 6 digit yang telah dikirim ke email Anda
				</p>
			</div>


			<form class="mt-8 space-y-6" action="{{ route('otp.verify.post') }}" method="POST">
				@csrf

				<div>
					<label for="otp" class="sr-only">Kode OTP</label>
					<div class="flex justify-center">
						<input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6"
							required
							class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm text-center text-2xl tracking-widest font-semibold"
							placeholder="000000" autofocus>
					</div>
					@error('otp')
					<p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
					@enderror
				</div>


				<div>
					<button type="submit"
						class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
						Verifikasi
					</button>
				</div>
			</form>
			<div class="flex items-center justify-between">
				<div class="text-sm">
					<form action="{{ route('otp.resend') }}" method="POST" class="inline">
						@csrf
						<button type="submit" class="font-medium text-indigo-600 hover:text-indigo-500">
							Kirim ulang kode
						</button>
					</form>
				</div>
			</div>

			<div class="text-center">
				<p class="text-xs text-gray-500">
					Kode OTP berlaku selama 5 menit
				</p>
			</div>
		</div>
	</div>

	<script>
		// Auto-format input OTP
        const otpInput = document.getElementById('otp');
        
        otpInput.addEventListener('input', function(e) {
            // Hanya izinkan angka
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-submit ketika 6 digit sudah diisi
            if (this.value.length === 6) {
                // Optional: auto-submit form
                // this.form.submit();
            }
        });

        // Paste handler untuk OTP
        otpInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text');
            const numericData = pastedData.replace(/[^0-9]/g, '').slice(0, 6);
            this.value = numericData;
        });
	</script>
</x-app-layout>