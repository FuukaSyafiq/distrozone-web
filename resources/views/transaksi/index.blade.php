<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Pesanan Saya') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6">
					<!-- Tabs -->
					<div class="border-b border-gray-200 mb-6">
						<nav class="-mb-px flex space-x-8" aria-label="Tabs">
							<a href="{{ route('transaksi.render', ['status' => 'PENDING']) }}"
								class="tab-link {{ (!request('status') || request('status') === 'PENDING') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
								Pending
								@if(isset($counts['PENDING']) && $counts['PENDING'] > 0)
								<span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs">{{
									$counts['PENDING'] }}</span>
								@endif
							</a>
							<a href="{{ route('transaksi.render', ['status' => 'ACC_KASIR']) }}"
								class="tab-link {{ request('status') === 'ACC_KASIR' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
								Diproses
								@if(isset($counts['ACC_KASIR']) && $counts['ACC_KASIR'] > 0)
								<span class="ml-2 bg-yellow-100 text-yellow-600 py-0.5 px-2.5 rounded-full text-xs">{{
									$counts['ACC_KASIR'] }}</span>
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
                                            @if($transaksi->status === 'PENDING') bg-blue-100
                                            @elseif($transaksi->status === 'ACC_KASIR') text-yellow-800
                                            @elseif($transaksi->status === 'DIKIRIM')
                                            @elseif($transaksi->status === 'SUKSES')
                                            @elseif($transaksi->status === 'GAGAL')
                                            @endif
                                            text-xs font-medium px-3 py-1 rounded-full">
									{{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}
								</span>
							</div>

							



							<!-- Details -->
							<div class="grid grid-cols-2 gap-4 mb-4">
								<div>
									<p class="text-xs text-gray-500 mb-1">Jenis Transaksi</p>
									<p class="text-sm font-medium">{{ $transaksi->jenis_transaksi }}</p>
								</div>
								<div>
									<p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
									<p class="text-sm font-medium">{{ $transaksi->metode_pembayaran }}</p>
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
								<a href="{{ route('transaksi.cetak.pdf', $transaksi->id_transaksi) }}"
									class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
									Cetak
								</a>

								{{-- @if($transaksi->status === 'DIKIRIM')
								<form action="{{ route('orders.confirm', $transaksi->id_transaksi) }}" method="POST"
									class="inline">
									@csrf
									<button type="submit"
										class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
										Terima Pesanan
									</button>
								</form>
								@endif --}}
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
</x-app-layout>