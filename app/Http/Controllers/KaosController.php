<?php

namespace App\Http\Controllers;

use App\Models\Kaos;
use Illuminate\Http\Request;

class KaosController extends Controller
{
    public function search(Request $request) {
        $q = $request->query('q');
        $kaoss = Kaos::getAllKaosWithName($q);

        return view('kaos.search', ['kaoss' => $kaoss, 'q' => $q]);
    }

}
