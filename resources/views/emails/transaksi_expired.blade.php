<h2>Halo {{ $transaksi->customer->nama }},</h2>

<p>Mohon maaf, batas waktu pembayaran untuk pesanan <strong>#{{ $transaksi->kode_transaksi }}</strong> telah berakhir.
</p>

<div style="background-color: #fff5f5; border-left: 4px solid #f56565; padding: 15px; margin: 20px 0;">
	<p style="color: #c53030; margin: 0;">
		<strong>Status Pesanan: KEDALUWARSA (DIBATALKAN OTOMATIS)</strong>
	</p>
</div>

<p>Sesuai dengan kebijakan kami, pesanan yang tidak dibayar dalam batas waktu yang ditentukan akan dibatalkan secara
	otomatis agar stok dapat tersedia kembali bagi pelanggan lain.</p>

<h3>🛒 Detail Pesanan yang Dibatalkan:</h3>
<table border="0" cellpadding="10" style="border-collapse: collapse; width: 100%;">
	<thead>
		<tr style="background-color: #f8f8f8; border-bottom: 2px solid #eee;">
			<th align="left">Produk</th>
			<th align="right">Qty</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($transaksi->details as $detail)
		<tr style="border-bottom: 1px solid #eee;">
			<td>
				<strong>{{ $detail->kaos_varian->kaos->nama_kaos }}</strong><br>
				<small>{{ $detail->kaos_varian->warna->label }} - {{ $detail->kaos_varian->ukuran->ukuran }}</small>
			</td>
			<td align="right">{{ $detail->qty }} pcs</td>
		</tr>
		@endforeach
	</tbody>
</table>

<p style="margin-top: 20px;">
	<strong>Total Tagihan sebelumnya: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong>
</p>

<hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">

<p><strong>Masih ingin memiliki produk ini?</strong><br>
	Jangan khawatir! Anda dapat melakukan pemesanan ulang melalui website kami selama stok masih tersedia.</p>

<div style="margin-top: 25px;">
	<a href="{{ url('/') }}"
		style="background-color: #319795; color: white; padding: 12px 20px; text-decoration: none; rounded: 5px; font-weight: bold;">
		Belanja Lagi di Distrozone
	</a>
</div>

<p style="margin-top: 30px;">Terima kasih atas pengertian Anda. 🙏</p>

<p>Salam,<br>
	<strong>Tim Distrozone</strong>
</p>