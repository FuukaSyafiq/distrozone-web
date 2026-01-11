<x-app-layout>
	<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-6">
		<div class="max-w-md w-full text-center">

			<!-- Error Code -->
			<h1 class="text-7xl font-extrabold text-gray-900 dark:text-white">
				404
			</h1>

			<!-- Title -->
			<h2 class="mt-4 text-2xl font-semibold text-gray-800 dark:text-gray-200">
				Halaman Tidak Ditemukan
			</h2>

			<!-- Description -->
			<p class="mt-3 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
				Maaf, halaman yang kamu cari tidak tersedia atau sudah dipindahkan.
			</p>

			<!-- Actions -->
			<div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
				<a href="{{ url()->previous() }}"
					class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-6 py-3 text-sm font-semibold text-white hover:bg-gray-800 transition dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200">
					<i class="bi bi-arrow-left"></i>
					Kembali
				</a>

				<a href="{{ url('/') }}"
					class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
					Ke Beranda
				</a>
			</div>

		</div>
	</div>
</x-app-layout>