<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function create() {
        
        $cartItems = KeranjangDetail::getKeranjangUserLogin();
        // dd($cartItems->toJson());

        return view('cart.index', ['cartItems' => $cartItems]);
    }

    public function delete($id) {
        KeranjangDetail::where('id_keranjang_detail', $id)->delete();

        return redirect()->back();
    }
}
