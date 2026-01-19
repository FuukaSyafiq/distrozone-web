<?php

namespace App\Http\Controllers;

use App\Helpers\CartStatus;
use App\Helpers\KeranjangStatus;
use App\Models\KaosVariant;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    public function create() {
        
        $cartItems = KeranjangDetail::getKeranjangUserLogin();
        // dd($cartItems->toJson());

        return view('cart.index', ['cartItems' => $cartItems]);
    }

    public function belilangsung(Request $request, $id_varian)
    {
        $user = auth()->user();
        $quantity = $request->input('quantity');

        $KaosVariant = KaosVariant::findOrFail($id_varian);

        if (!auth()->check()) {
            return redirect()->to('login');
            return;
        }

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

            $hargaSatuan = $KaosVariant->kaos->harga_jual;

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

            return view('checkout.details', [
                'keranjang' => collect([$keranjangDetail]),
                'keranjangUtama' => $keranjang,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // kalau perlu log error
            logger()->error($e);

            return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function delete(Request $request, $id) {
        $input = $request->input('quantity');
        $keranjang = KeranjangDetail::where('id_keranjang_detail', $id)->first();

        KaosVariant::where('id', $keranjang->id_kaos_varian)->increment('stok_kaos', $input);
        $keranjang->delete();

        return redirect()->back();
    }

    public function check(Request $request)
    {
        // Ambil array id keranjang detail dari form
        $detailIds = $request->input('keranjang_detail_ids', []);

        // dd($detailIds);
        // Kalau kosong, redirect balik dengan pesan
        if (empty($detailIds)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk checkout.');
        }

        // Ambil data KeranjangDetail yang aktif
        $cartItems = KeranjangDetail::with(['kaos_varian', 'keranjang'])
            ->whereIn('id_keranjang_detail', $detailIds)
            ->get();

        return view('checkout.details', [
            'keranjang' => $cartItems,
            'keranjangUtama' => $cartItems[0]->keranjang,
        ]);
    }
}
