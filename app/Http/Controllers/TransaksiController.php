<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function create(Request $request)
    {
        $kaosId = $request->query('kaos');
        $quantity = $request->query('quantity');

        
        return view('checkout.details', ['kaos_id' => $kaosId, 'quantity' => $quantity]);
    }
}
