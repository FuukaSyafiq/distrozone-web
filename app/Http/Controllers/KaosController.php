<?php

namespace App\Http\Controllers;

use App\Models\Kaos;
use App\Models\KaosVariant;
use Illuminate\Http\Request;

class KaosController extends Controller
{
    public function detail(Request $request, string $id)
    {

        $kaos = Kaos::getKaosById($id);

        // override relasi variants
        $kaos->setRelation(
            'variants',
            $kaos->variants
                ->unique('warna_id')
                ->values()
        );

        // ukuran awal
        $firstVariant = $kaos->variants->first();

        $ukurans = KaosVariant::with('ukuran')
            ->where('kaos_id', $kaos->id_kaos)
            ->where('warna_id', $firstVariant->warna_id)
            ->get();

        return view('kaos.detail', compact('kaos', 'ukurans'));
    }

    public function search(Request $request)
    {
        $q = $request->query('q');
        $kaoss = Kaos::searchKaos($q);

        return view('index', ['kaoss' => $kaoss, 'title' => $q]);
    }
}
