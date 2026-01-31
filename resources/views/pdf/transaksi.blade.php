<!DOCTYPE html>
<html>

<head>
    <title>Detail Transaction</title>
    <!-- Import Tailwind classes via Curlwind -->
    <link rel="stylesheet"
        href="https://cdn.curlwind.com?classes=text-center,text-2xl,font-bold,text-lg,italic,mb-4,mb-6,mt-6,p-6,mx-auto,my-24,my-auto,my-6,w-4/5,border,border-black,bg-gray-200,py-2,px-4,w-48,w-full,h-auto">
</head>

<body>
    @php
    use Illuminate\Support\Facades\Storage;
    @endphp
    <!-- Title -->
    <h1 class="text-center text-2xl font-bold mb-4">Transaksi</h1>
    <hr class="border-black mb-4">


    <div class="info-section-wrap">
        <div class="info-section">

        </div>
        <div class="info-section-2">
            <p><strong>Tanggal : </strong>{{ Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
            <p><strong>Status pembayaran : </strong>{{ $pembayaran->status }}</p>
            <p><strong>Status transaksi : </strong>{{ $transaksi->status }}</p>

            <br />
            @if($transaksi->customer)
            <div class="customer-info">
                <p><strong>Nama : </strong>{{ $transaksi->customer->nama }}</p>
                <p><strong>Email : </strong>{{ $transaksi->customer->email }}</p>
                <p><strong>No telepon : </strong>{{ $transaksi->customer->no_telepon }}</p>

                {{-- Nested check untuk Kota & Provinsi --}}
                @if($transaksi->customer->kota)
                <p><strong>Kota : </strong>{{ $transaksi->customer->kota->kota }}</p>
                @if($transaksi->customer->kota->provinsi)
                <p><strong>Provinsi : </strong>{{ $transaksi->customer->kota->provinsi->provinsi }}</p>
                @endif
                @endif

                <p><strong>Alamat : </strong>{{ $transaksi->customer->alamat_lengkap }}</p>
            </div>
            @endif
            <p><strong>Metode pembayaran : </strong>{{ $transaksi->metode_pembayaran }}</p>
            <p><strong>Total harga : </strong>Rp. {{
                number_format($transaksi->total_harga, 0, ',',
                '.')}}</p>
            <p><strong>Ongkir : </strong>Rp. {{
                number_format($transaksi->ongkir, 0, ',',
                '.')}}</p>
            <div class="mt-6"
                style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
                <p style="font-weight: bold; color: #374151; margin: 0 0 10px 0;">Bukti Transfer :</p>

                @if($pembayaran->bukti_transfer)
                <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                    <div
                        style="border: 1px solid #d1d5db; padding: 8px; display: inline-block; border-radius: 8px; background-color: white; overflow: hidden;">
                        <a href="{{ Storage::disk('s3')->url($pembayaran->bukti_transfer) }}" target="_blank"
                            title="Klik untuk memperbesar" style="text-decoration: none;">
                            <img src="{{ Storage::disk('s3')->url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer"
                                style="height: 250px; display: block; cursor: zoom-in; transition: opacity 0.3s;"
                                onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        </a>
                    </div>
                    <p style="font-size: 12px; color: #9ca3af; font-style: italic; margin-top: 5px;">Klik gambar untuk
                        memperbesar
                    </p>
                </div>
                @else
                <p
                    style="font-style: italic; color: #6b7280; font-size: 14px; padding: 10px 20px; background-color: #f3f4f6; border: 1px dashed #d1d5db; border-radius: 6px;">
                    Belum ada bukti transfer diunggah
                </p>
                @endif
            </div>
        </div>
    </div>

    <div class="my-6">
        <h1 class="text-xl font-bold mb-4">Detail Pembelian</h1>
        <table class="w-full mx-auto my-3 border border-black">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-black py-2 px-4 text-center">No</th>
                    <th class="border border-black py-2 px-4 text-center">Kaos</th>
                    <th class="border border-black py-2 px-4 text-center">Tipe kaos</th>
                    <th class="border border-black py-2 px-4 text-center">Quantity</th>
                    <th class="border border-black py-2 px-4 text-center">Harga Satuan</th>
                    <th class="border border-black py-2 px-4 text-center">Total Harga</th>
                    <th class="border border-black py-2 px-4 text-center">Gambar</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iterate through each transaction -->
                @foreach ($transaksi_detail as $t)
                <tr>
                    <td class="border border-black py-2 px-4 text-center">{{ $loop->iteration }}</td>
                    <td class="border border-black py-2 px-4 text-center">{{ $t->kaos_varian->kaos->nama_kaos }}</td>
                    <td class="border border-black py-2 px-4 text-center">{{ $t->kaos_varian->kaos->type->type }}</td>
                    <td class="border border-black py-2 px-4 text-center">{{ $t->qty }}</td>
                    <td class="border border-black py-2 px-4 text-center">Rp. {{
                        number_format($t->harga_satuan, 0, ',',
                        '.')}}</td>
                    <td class="border border-black py-2 px-4 text-center">Rp. {{
                        number_format($t->subtotal, 0, ',',
                        '.')}}</td>


                    <td class="border border-black py-2 px-4 text-center">
                        @if(!empty($t->kaos_varian->image_path))
                        <img src="{{ Storage::disk('s3')->url($t->kaos_varian->image_path) }}" alt="Kaos"
                            style="width:80px; height:auto;">
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
</body>

</html>