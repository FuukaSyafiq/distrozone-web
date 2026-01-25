<?php

namespace App\Http\Controllers;

use App\Helpers\CartStatus;
use App\Helpers\KeranjangStatus;
use App\Helpers\Kode;
use App\Helpers\PembayaranStatus;
use App\Helpers\TransaksiStatus;
use App\Mail\TransaksiExpiredMail;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{

    public function langsung(Request $request)
    {
        $keranjangDetailId = $request->query('keranjang_details');

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

    public function pesan(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer = auth()->user();

            $keranjangDetailId = $request->keranjang_details;

            $paymentMethod = $request->input('payment_method');
            $ongkirTarifId = $request->input('ongkir_tarif_id');
            $ongkirTotal = $request->input('ongkir_total');
            $total = $request->input('total');

            // 6️⃣ Transaksi (HEADER)
            $transaksi = Transaksi::create([
                'kode_transaksi'     => Kode::GenerateKodeTransaksi(),
                'jenis_transaksi'    => 'ONLINE',
                'metode_pembayaran'  => $paymentMethod,
                'total_harga'        => $total,
                'id_kasir'           => null,
                'id_customer'       => $customer->id_user,
                'id_ongkir'         => $ongkirTarifId,
                'ongkir'            => $ongkirTotal,
                'expires_at'       => now()->addHours(24),
            ]);
            $keranjangDetails = KeranjangDetail::whereIn('id_keranjang_detail', $keranjangDetailId)->get();
            // 9️⃣ Update status keranjang → CHECKOUT
            $keranjangUser = Keranjang::where('id_customer', $customer->id_user)->where('status', CartStatus::CHECKOUT)->first();
            if (!$keranjangUser) {
                $keranjangUser =  Keranjang::create([
                    'id_customer' => $customer->id_user,
                    'status' => CartStatus::CHECKOUT
                ]);
            }
            // 7️⃣ Transaksi Detail
            foreach ($keranjangDetails as $item) {
                KeranjangDetail::where('id_keranjang_detail', $item->id_keranjang_detail)->update(["id_keranjang" => $keranjangUser->id_keranjang]);

                TransaksiDetail::create([
                    'id_transaksi'   => $transaksi->id_transaksi,
                    'id_kaos_varian' => $item->id_kaos_varian,
                    'harga_satuan'   => $item->harga_satuan,
                    'harga_pokok' => $item->kaos_varian->harga_pokok,
                    'qty'            => $item->qty,
                    'subtotal'       => $item->subtotal,
                ]);
            }

            DB::commit();

            return redirect()->route('transaksi.render');
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getTraceAsString());
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function bayar(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $transaksi_id = $id;
            $user = auth()->user();
            $catatan = $request->input('catatan', null);
            $file = $request->file('bukti_pembayaran');

            $transaksi = Transaksi::where('id_transaksi', $transaksi_id)->where('id_customer', $user->id_user)->first();

            $path = Storage::disk('s3')->put(
                'bukti-transfer', // folder di bucket
                $file
            );

            // 8️⃣ Pembayaran
            Pembayaran::create([
                'status'        => PembayaranStatus::MENUNGGU,
                'no_invoice'    => Kode::GenerateInvoiceNumber(),
                'id_transaksi'  => $transaksi->id_transaksi,
                'bukti_transfer' => $path,
                'catatan'       => $catatan
            ]);
            $transaksi->update([
                'status' => TransaksiStatus::PENDING,
                'expires_at' => null
            ]);
            DB::commit();
            return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi dari admin.');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage(), $e->getTraceAsString());
            return back()->with('error', $e->getMessage());
        }
    }

    public function transaksi(Request $request)
    {
        $userId = Auth::id();
        $status = $request->get('status', 'PENDING');

        $expiredList = Transaksi::where('id_customer', $userId)
            ->where('status', TransaksiStatus::BELUMBAYAR)
            ->where('expires_at', '<=', now())
            ->get(); // Kita ambil datanya, bukan langsung update

        if ($expiredList->isNotEmpty()) {
            foreach ($expiredList as $transaksi) {
                // 2. Update status masing-masing transaksi menjadi GAGAL
                $transaksi->update(['status' => TransaksiStatus::GAGAL]);

                // 3. Kembalikan stok (Opsional tapi sangat disarankan)
                foreach ($transaksi->details as $detail) {
                    $detail->kaos_varian->increment('stok', $detail->qty);
                }

                // 4. Kirim Email untuk transaksi ini
                // Sekarang $transaksi adalah objek lengkap, bukan cuma angka
                Mail::to(Auth::user()->email)->send(new TransaksiExpiredMail($transaksi));
            }
        }

        // Get transaksi by status
        $transaksis = Transaksi::where('id_customer', $userId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        // Get counts for each status
        $counts = [
            'BELUMBAYAR' => Transaksi::where('id_customer', $userId)->where('status', TransaksiStatus::BELUMBAYAR)->count(),
            'PENDING' => Transaksi::where('id_customer', $userId)->where('status', TransaksiStatus::PENDING)->count(),
            'DIKIRIM' => Transaksi::where('id_customer', $userId)->where('status', TransaksiStatus::DIKIRIM)->count(),
            'SUKSES' => Transaksi::where('id_customer', $userId)->where('status', TransaksiStatus::SUKSES)->count(),
            'GAGAL' => Transaksi::where('id_customer', $userId)->where('status', TransaksiStatus::GAGAL)->count(),
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
