<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kaos;
use App\Models\KaosVariant;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    public function gets()
    {

        $kaoss = Kaos::getAllKaos();
       
        return view('index', ['kaoss' => $kaoss, 'title' => 'Koleksi Kaos Kami']);
    }
    public function store(Request $request) {

        return redirect()->to("/");
    }
}
