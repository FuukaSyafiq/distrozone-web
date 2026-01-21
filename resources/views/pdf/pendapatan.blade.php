<!DOCTYPE html>
<html>

<head>
	<title>Detail Transaction</title>
	<!-- Import Tailwind classes via Curlwind -->
	<link rel="stylesheet"
		href="https://cdn.curlwind.com?classes=text-center,text-2xl,font-bold,text-lg,italic,mb-4,mb-6,mt-6,p-6,mx-auto,my-24,my-auto,my-6,w-4/5,border,border-black,bg-gray-200,py-2,px-4,w-48,h-auto">
</head>

<body>

	<!-- Title -->
	<h1 class="text-center text-2xl font-bold mb-4">Pendapatan</h1>
	<hr class="border-black mb-4">


	<div class="info-section-wrap">
		<div class="info-section">

		</div>
		<div class="info-section-2">
			<p><strong>Total Pendapatan</strong></p>
			<p><strong>Mulai Tanggal : </strong>{{ Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }}</p>
			<p><strong>Sampai Tanggal : </strong>{{ Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}</p>
			<p><strong>Total keuntungan : </strong>Rp. {{
				number_format($totalKeuntungan, 0, ',',
				'.')}}</p>
		</div>
	</div>

	<div class="my-6">
		<table class="w-4/5 mx-auto my-24 border border-black">
			<thead class="bg-gray-200">
				<tr>
					<th class="border border-black py-2 px-4 text-center">No</th>
					<th class="border border-black py-2 px-4 text-center">Omset</th>
					<th class="border border-black py-2 px-4 text-center">Ongkir</th>
					<th class="border border-black py-2 px-4 text-center">Modal</th>
					<th class="border border-black py-2 px-4 text-center">Keuntungan</th>
					<th class="border border-black py-2 px-4 text-center">Tanggal</th>
				</tr>
			</thead>
			<tbody>
				<!-- Iterate through each transaction -->
				@foreach ($records as $record)
				<tr>
					<td class="border border-black py-2 px-4 text-center">{{ $loop->iteration }}</td>
					<td class="border border-black py-2 px-4 text-center">Rp. {{
						number_format($record->jumlah, 0, ',',
						'.')}}</td>
					<td class="border border-black py-2 px-4 text-center">Rp. {{
						number_format($record->transaksi->ongkir, 0, ',',
						'.')}}</td>
					<td class="border border-black py-2 px-4 text-center">Rp. {{ number_format($record->modal, 0, ',',
						'.')
						}}</td>
					<td class="border border-black py-2 px-4 text-center">Rp. {{
						number_format($record->keuntungan, 0, ',',
						'.')}}</td>
					<td class="border border-black py-2 px-4 text-center">{{
						Carbon\Carbon::parse($record->tanggal)->translatedFormat('d F Y') }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
</body>

</html>