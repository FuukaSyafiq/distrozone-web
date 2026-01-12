<?php

namespace App\Http\Controllers;

use App\Models\Kaos;
use Illuminate\Http\Request;

class KaosController extends Controller
{
    public function detail(Request $request, string $id) {

        $kaos = Kaos::getKaosById($id);
        return view('kaos.detail', ['kaos' => $kaos]);
    }

    public function search(Request $request) {
        $q = $request->query('q');
        $kaoss = Kaos::searchKaos($q);

        return view('kaos.search', ['kaoss' => $kaoss, 'q' => $q]);
    }

}
