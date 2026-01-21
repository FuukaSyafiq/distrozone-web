<h2>Halo {{ $transaksi->customer->nama }}</h2>

<p>Kaos akan dikirim ke alamat tujuan anda</p>
<p>Kota: {{ $transaksi->customer->kota->kota }}</p>
<p>Provinsi: {{ $transaksi->customer->kota->provinsi->provinsi }}</p>
<p>Alamat lengkap: {{ $transaksi->customer->alamat_lengkap }}</p>


<p>Berikut daftar kaos yang akan dikirim:</p>

<ul>
	@foreach ($transaksi->details as $detail)
	<li>
		Kaos: {{ $detail->kaos_varian->kaos->nama_kaos }} <br>
		Merek: {{ $detail->kaos_varian->kaos->merek->merek }} <br>
		Warna: {{ $detail->kaos_varian->warna->label }} <br>
		Ukuran: {{ $detail->kaos_varian->ukuran->ukuran }} <br>
		Jumlah: {{ $detail->qty }}
	</li>
	<br>
	@endforeach
</ul>

<p>Status pesanan sekarang: <b>DIKIRIM</b></p>

<p>Terima kasih 🙏, Salam Distrozone</p>
</body>

</html>