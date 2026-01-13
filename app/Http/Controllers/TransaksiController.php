<?php

namespace App\Http\Controllers;

use App\Helpers\Kode;
use App\Helpers\PembayaranStatus;
use App\Helpers\TransaksiStatus;
use App\Models\KeranjangDetail;
use App\Models\Ongkir;
use App\Models\Pembayaran;
use App\Models\Pendapatan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function create(Request $request)
    {
        $keranjangIds = $request->query('keranjang'); // contoh: [1,2,5]

        if (empty($keranjangIds) || !is_array($keranjangIds)) {
            return redirect()->to('/');
        }

        $keranjangDipilih = KeranjangDetail::with([
            'kaos_varian.kaos',
            'kaos_varian.ukuran',
            'kaos_varian.warna',
        ])
            ->whereIn('id_keranjang_detail', $keranjangIds)
            ->get();

        return view('checkout.details', ['keranjang' => $keranjangDipilih]);
    }

    public function bayar(Request $request)
    {
        DB::beginTransaction();

        try {
            $customer = auth()->user();

            $keranjangIds = $request->input('keranjang'); // array id_keranjang_detail
            $metode = $request->input('payment_selected');
            $ongkir = Ongkir::findOrFail($request->input('ongkir_id'));

            // Ambil item keranjang user
            $items = KeranjangDetail::with(['kaos_varian.kaos'])
                ->whereIn('id_keranjang_detail', $keranjangIds)
                ->whereHas(
                    'keranjang',
                    fn($q) =>
                    $q->where('id_customer', $customer->id_user)
                )
                ->get();

            if ($items->isEmpty()) {
                throw new \Exception('Keranjang kosong');
            }

            // Hitung subtotal
            $subtotal = $items->sum(fn($item) => $item->subtotal);
            $totalOngkir = $ongkir->harga;
            $totalBayar = $subtotal + $totalOngkir;

            // 1️⃣ Transaksi (HEADER)
            $transaksi = Transaksi::create([
                'kode_transaksi' => Kode::GenerateKodeTransaksi(),
                'jenis_transaksi' => 'ONLINE',
                'metode_pembayaran' => strtoupper($metode),
                'total_harga' => $totalBayar,
                'id_customer' => $customer->id_user,
                'id_ongkir' => $ongkir->id,
                'ongkir' => $totalOngkir,
                'status' => TransaksiStatus::PENDING,
            ]);

            // 2️⃣ Transaksi Detail
            foreach ($items as $item) {
                TransaksiDetail::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_kaos_varian' => $item->id_kaos_varian,
                    'harga_satuan' => $item->harga_satuan,
                    'qty' => $item->qty,
                    'subtotal' => $item->subtotal,
                ]);

                // Kurangi stok
                $item->kaos_varian->decrement('stok_kaos', $item->qty);
            }

            // 3️⃣ Pembayaran
            Pembayaran::create([
                'status' => PembayaranStatus::MENUNGGU,
                'no_invoice' => Kode::GenerateInvoiceNumber(),
                'id_transaksi' => $transaksi->id_transaksi,
            ]);

            // 4️⃣ Update keranjang
            KeranjangDetail::whereIn('id_keranjang_detail', $keranjangIds)
                ->update(['status' => 'CHECKOUT']);

            DB::commit();

            return redirect()->route('checkout.success', $transaksi->id_transaksi);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
