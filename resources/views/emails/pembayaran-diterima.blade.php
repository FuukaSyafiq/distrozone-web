<h2>Pembayaran Diterima</h2>

<p>Halo {{ $transaksi->customer->nama }},</p>

<p>
	Pembayaran untuk transaksi
	<b>{{ $transaksi->kode_transaksi }}</b>
	Telah kami terima dan kami akan segera memproses pesanan anda
</p>

<p>Total: <b>Rp {{ number_format($transaksi->total_harga) }}</b></p>

<p>Status pesanan sekarang: <b>DIPROSES</b></p>

<p>Terima kasih 🙏, Salam Distrozone</p>