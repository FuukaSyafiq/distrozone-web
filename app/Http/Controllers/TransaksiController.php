<?php

namespace App\Http\Controllers;

use App\Helpers\CartStatus;
use App\Helpers\KeranjangStatus;
use App\Helpers\Kode;
use App\Helpers\PembayaranStatus;
use App\Helpers\TransaksiStatus;
use App\Models\JamOperasional;
use App\Models\Kaos;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use App\Models\Ongkir;
use App\Models\Pembayaran;
use App\Models\Pendapatan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{

    public function langsung(Request $request)
    {
        $keranjangDetailId = $request->query('keranjang_details'); // single ID

        if (!$keranjangDetailId) {
            return redirect('/');
        }

        // 1. Ambil keranjang aktif milik user
        $keranjang = Keranjang::where('id_customer', auth()->id())
            ->where('status', CartStatus::AKTIF)->where('id_keranjang', $keranjangDetailId)
            ->first();

        if (!$keranjang) {
            return redirect('/')->with('error', 'Keranjang tidak ditemukan');
        }

        return view('checkout.details', [
            'keranjang' => $keranjang->details,
            'keranjangUtama' => $keranjang,
        ]);
    }
    public function selesai($id)
    {
        Transaksi::where('id_transaksi', $id)->where('id_customer', Auth::id())->update(['status' => TransaksiStatus::SUKSES]);

        return redirect()->back();
    }

    public function bayar(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer = auth()->user();
            $file = $request->file('bukti_transfer');

            $hariSekarang = Carbon::now()->translatedFormat('l');


            $path = Storage::disk('s3')->put(
                'bukti-transfer', // folder di bucket
                $file
            );
            $keranjangId   = $request->input('keranjang_id');
            $paymentMethod = $request->input('payment_method');
            $ongkirTarifId = $request->input('ongkir_tarif_id');
            $ongkirTotal = $request->input('ongkir_total');
            $total = $request->input('total');


            // 1️⃣ Ambil item keranjang user
            $items = Keranjang::where('id_keranjang', $keranjangId)
                ->where('status', KeranjangStatus::AKTIF)->where('id_customer', $customer->id_user)
                ->first();

            // 6️⃣ Transaksi (HEADER)
            $transaksi = Transaksi::create([
                'kode_transaksi'     => Kode::GenerateKodeTransaksi(),
                'jenis_transaksi'    => 'ONLINE',
                'metode_pembayaran'  => 'TRANSFER',
                'total_harga'        => $total,
                'id_kasir' => null,
                'id_customer'        => $customer->id_user,
                'id_ongkir'          => $ongkirTarifId,
                'ongkir'             => $ongkirTotal,
                'status'             => TransaksiStatus::PENDING,
            ]);

            // 7️⃣ Transaksi Detail
            foreach ($items->details as $item) {
                $kaos = Kaos::where('id_kaos', $item->kaos_varian->kaos_id)->first();

                TransaksiDetail::create([
                    'id_transaksi'   => $transaksi->id_transaksi,
                    'id_kaos_varian' => $item->id_kaos_varian,
                    'harga_satuan'   => $item->harga_satuan,
                    'harga_pokok' => $kaos->harga_pokok,
                    'qty'            => $item->qty,
                    'subtotal'       => $item->subtotal,
                ]);
            }


            // 8️⃣ Pembayaran
            Pembayaran::create([
                'status'        => PembayaranStatus::MENUNGGU,
                'no_invoice'    => Kode::GenerateInvoiceNumber(),
                'id_transaksi'  => $transaksi->id_transaksi,
                'bukti_transfer' => $path
            ]);

            // 9️⃣ Update status keranjang → CHECKOUT
            Keranjang::where('id_keranjang', $items->id_keranjang)
                ->update(['status' => 'CHECKOUT']);


            DB::commit();

            // return redirect()->route('checkout.success', $transaksi->id_transaksi);
            return redirect()->route('index', $transaksi->id_transaksi);
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage(), $e->getTraceAsString());
            // return back()->with('error', $e->getMessage());
        }
    }

    public function transaksi(Request $request)
    {

        $status = $request->get('status', 'PENDING');

        // Get transaksi by status
        $transaksis = Transaksi::where('id_customer', Auth::id())
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get counts for each status
        $counts = [
            'PENDING' => Transaksi::where('id_customer', Auth::id())->where('status', 'PENDING')->count(),
            'ACC_KASIR' => Transaksi::where('id_customer', Auth::id())->where('status', 'ACC_KASIR')->count(),
            'DIKIRIM' => Transaksi::where('id_customer', Auth::id())->where('status', 'DIKIRIM')->count(),
            'SUKSES' => Transaksi::where('id_customer', Auth::id())->where('status', 'SUKSES')->count(),
            'GAGAL' => Transaksi::where('id_customer', Auth::id())->where('status', 'GAGAL')->count(),
        ];
        return view('transaksi.index', compact('transaksis', 'counts'));
    }

    public function show(string $id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'customer', 'ongkir'])
            ->where('id_customer', Auth::id())
            ->findOrFail($id);

        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Confirm order received (customer confirms delivery)
     */
    public function confirm(string $id)
    {
        $transaksi = Transaksi::where('id_customer', Auth::id())
            ->where('status', 'DIKIRIM')
            ->findOrFail($id);

        $transaksi->update([
            'status' => 'SUKSES'
        ]);

        return redirect()->route('transaksi.index', ['status' => 'SUKSES'])
            ->with('success', 'Pesanan berhasil dikonfirmasi. Terima kasih!');
    }
}
