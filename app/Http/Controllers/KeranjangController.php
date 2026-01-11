<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function create() {
        
        $cartItems = KeranjangDetail::getAllKeranjang();

        return view('cart.index', ['cartItems' => $cartItems]);
    }  
}
