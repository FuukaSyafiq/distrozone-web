<h2>Halo {{ $transaksi->customer->nama }},</h2>

<p>Pembayaran Anda untuk pesanan <strong>#{{ $transaksi->kode_transaksi }}</strong> telah berhasil diverifikasi!</p>

<p>Pesanan Anda sekarang dalam proses pengiriman dengan detail sebagai berikut:</p>

<h3>📦 Informasi Pengiriman</h3>
<ul>
	<li><strong>Kota:</strong> {{ $transaksi->customer->kota->kota }}</li>
	<li><strong>Provinsi:</strong> {{ $transaksi->customer->kota->provinsi->provinsi }}</li>
	<li><strong>Alamat Lengkap:</strong> {{ $transaksi->customer->alamat_lengkap }}</li>
</ul>

<h3>🛒 Detail Pesanan</h3>
<table border="0" cellpadding="10" style="border-collapse: collapse; width: 100%;">
	@foreach ($transaksi->details as $detail)
	<tr style="border-bottom: 1px solid #eee;">
		<td>
			<strong>{{ $detail->kaos_varian->kaos->nama_kaos }}</strong><br>
			Merek: {{ $detail->kaos_varian->kaos->merek->merek }}<br>
			Warna: {{ $detail->kaos_varian->warna->label }}<br>
			Ukuran: {{ $detail->kaos_varian->ukuran->ukuran }}
			Quantity: {{ $detail->qty }} pcs
		</td>
	</tr>
	@endforeach
</table>

<p><strong>🚚 Status Pengiriman:</strong> Pesanan Anda sudah dikirim!</p>

<p>Jika pesanan anda sudah sampai jangan lupa untuk mengklik tombol selesai di web kami</p>

<p>Terima kasih telah berbelanja di Distrozone! 🙏</p>

<p>Salam,<br>
	<strong>Tim Distrozone</strong>
</p>