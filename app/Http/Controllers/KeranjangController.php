<?php

namespace App\Http\Controllers;

use App\Helpers\CartStatus;
use App\Helpers\KeranjangStatus;
use App\Helpers\NikVerified;
use App\Helpers\TransaksiStatus;
use App\Models\JamOperasional;
use App\Models\KaosVariant;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use App\Models\PaymentMethod;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    public function create()
    {

        $user = auth()->user();
        $transaksiSelesai = Transaksi::where('id_customer', $user->id_user)
            ->whereIn('status', [TransaksiStatus::SUKSES, TransaksiStatus::GAGAL])
            ->with('details') // Eager load agar tidak N+1 query
            ->get();

        DB::beginTransaction();
        $itemsDeleted = false;

        foreach ($transaksiSelesai as $transaksi) {
            foreach ($transaksi->details ?? [] as $detail) {
                if (!$detail) continue;
                $keranjang = Keranjang::where('id_customer', $user->id_user)->where('status', KeranjangStatus::CHECKOUT)->first();

                if (!$keranjang) continue;
                $deleted = KeranjangDetail::where('id_keranjang', $keranjang->id_keranjang)
                    ->delete();

                if ($deleted) $itemsDeleted = true;
                // Hapus item di keranjang yang sudah masuk ke transaksi selesai
            }
        }

        // Berikan pesan session jika ada pembersihan keranjang
        if ($itemsDeleted) {
            session()->flash('info', 'Keranjang Anda telah diperbarui (item yang sudah diproses telah dihapus).');
        }
        DB::commit();
        $cartItems = KeranjangDetail::getKeranjangUserLogin();

        return view('cart.index', ['cartItems' => $cartItems]);
    }

    public function belilangsung(Request $request, $id_varian)
    {
        $user = auth()->user();
        if (!auth()->check()) {
            return redirect("/login");
        }

        $quantity = $request->input('quantity');

        $KaosVariant = KaosVariant::findOrFail($id_varian);

        DB::beginTransaction();

        try {
            $keranjang = Keranjang::where('status', KeranjangStatus::AKTIF)
                ->where('id_customer', $user->id_user)
                ->first();

            if (!$keranjang) {
                $keranjang = Keranjang::create([
                    'status' => CartStatus::AKTIF,
                    'id_customer' => $user->id_user
                ]);
            }

            $hargaSatuan = $KaosVariant->harga_jual;

            $keranjangDetail = KeranjangDetail::create([
                'id_keranjang' => $keranjang->id_keranjang,
                'id_kaos_varian' => $id_varian,
                'harga_satuan' => $hargaSatuan,
                'qty' => $quantity,
                'subtotal' => $hargaSatuan * $quantity
            ]);

            KaosVariant::where('id', $id_varian)
                ->decrement('stok_kaos', $quantity);

            DB::commit();
            $isBuka = JamOperasional::isBuka('ONLINE');
            if (!$isBuka) {
                session()->flash('message', "Toko sedang tutup untuk pemesanan online. Silakan coba lagi nanti.");
                return redirect()->route('cart');
            }

            $paymentmethods = PaymentMethod::where('is_active', true)->get();

            if ($user->nik_verified != NikVerified::APPROVED || !$user->email_verified_at || !$user->alamat_lengkap || !$user->kota_id) {
                $status = [];

                // Cek masing-masing kondisi secara spesifik
                if ($user->nik_verified != NikVerified::APPROVED) $status[] = "NIK atau Menunggu Konfirmasi ";
                if (!$user->email_verified_at) $status[] = "Email";
                if (!$user->alamat_lengkap) $status[] = "Alamat Lengkap";
                if (!$user->kota_id) $status[] = "Pilihan Kota";

                // Gabungkan pesan dengan tata bahasa yang benar (menggunakan koma dan 'dan')
                $lastItem = array_pop($status);
                $itemsString = count($status) ? implode(", ", $status) . " dan " . $lastItem : $lastItem;

                $message = "Akses ditolak: Mohon lengkapi {$itemsString} Anda pada menu Profil untuk dapat melanjutkan pesanan.";

                session()->flash('message', $message);

                return redirect()->route('cart');
            }

            return view('checkout.details', [
                'keranjang' => collect([$keranjangDetail]),
                'keranjangUtama' => $keranjang,
                'paymentmethods' => $paymentmethods
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // kalau perlu log error
            logger()->error($e);

            return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function delete(Request $request, $id)
    {
        $input = $request->input('quantity');
        $keranjang = KeranjangDetail::where('id_keranjang_detail', $id)->first();

        KaosVariant::where('id', $keranjang->id_kaos_varian)->increment('stok_kaos', $input);
        $keranjang->delete();

        return redirect()->back();
    }

    public function check(Request $request)
    {

        $user = auth()->user();
        // Ambil array id keranjang detail dari form
        $detailIds = $request->input('keranjang_detail_ids', []);

        // dd($detailIds);
        // Kalau kosong, redirect balik dengan pesan
        if (empty($detailIds)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk checkout.');
        }
        $isBuka = JamOperasional::isBuka('ONLINE');
        if (!$isBuka) {
            // dd($isBuka);
            session()->flash('message', "Toko sedang tutup untuk pemesanan online. Silakan coba lagi nanti.");
            return redirect()->route('cart');
        }
        // Ambil data KeranjangDetail yang aktif
        $cartItems = KeranjangDetail::with(['kaos_varian', 'keranjang'])
            ->whereIn('id_keranjang_detail', $detailIds)
            ->get();
        $paymentmethods = PaymentMethod::where('is_active', true)->get();

        if ($user->nik_verified != NikVerified::APPROVED || !$user->email_verified_at || !$user->alamat_lengkap || !$user->kota_id) {
            $status = [];

            // Cek masing-masing kondisi secara spesifik
            if ($user->nik_verified != NikVerified::APPROVED) $status[] = "NIK atau Menunggu Konfirmasi ";
            if (!$user->email_verified_at) $status[] = "Email";
            if (!$user->alamat_lengkap) $status[] = "Alamat Lengkap";
            if (!$user->kota_id) $status[] = "Pilihan Kota";

            // Gabungkan pesan dengan tata bahasa yang benar (menggunakan koma dan 'dan')
            $lastItem = array_pop($status);
            $itemsString = count($status) ? implode(", ", $status) . " dan " . $lastItem : $lastItem;

            $message = "Akses ditolak: Mohon lengkapi {$itemsString} Anda pada menu Profil untuk dapat melanjutkan pesanan.";

            session()->flash('message', $message);

            return redirect()->route('cart');
        }


        return view('checkout.details', [
            'keranjang' => $cartItems,
            'keranjangUtama' => $cartItems[0]->keranjang,
            'paymentmethods' => $paymentmethods
        ]);
    }
}
