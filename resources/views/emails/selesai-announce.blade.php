<h2>Halo {{ $transaksi->customer->nama }},</h2>

<p>Menurut catatan kami, pesanan <strong>#{{ $transaksi->kode_transaksi }}</strong> Anda sudah tiba di alamat tujuan!
</p>

<div style="background-color: #f0fdf4; border-left: 4px solid #22c55e; padding: 15px; margin: 20px 0;">
	<h3 style="color: #166534; margin-top: 0;">🎉 Pesanan Telah Diterima</h3>
	<p>Semoga Anda puas dengan produk dari **Distrozone**. Jangan lupa periksa kembali kelengkapan produk Anda.</p>
</div>
<div
	style="margin-top: 20px; padding: 15px; border: 1px dashed #e5e7eb; border-radius: 10px; background-color: #f9fafb;">
	<p style="margin-top: 0;"><strong>⚠️ Ada kendala dengan barangnya?</strong><br>
		Jika produk yang Anda terima cacat atau tidak sesuai, silakan hubungi kami sebelum menekan tombol "Selesai"
		melalui:</p>

	<p style="margin-bottom: 0;">
		<a href="https://wa.me/628816977857?text=Halo%20Admin,%20saya%20ingin%20komplain%20pesanan%20%23{{ $transaksi->kode_transaksi }}"
			target="_blank" style="color: #25d366; font-weight: bold; text-decoration: none; margin-right: 15px;">
			🟢 Chat WhatsApp
		</a>

		<a href="{{ url('/') }}" target="_blank" style="color: #4f46e5; font-weight: bold; text-decoration: none;">
			🔵 Livechat di Web
		</a>
	</p>
</div>

<p>Salam hangat,<br>
	<strong>Tim Distrozone</strong>
</p>