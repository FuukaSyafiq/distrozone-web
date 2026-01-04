<?php

namespace App\Livewire;

use App\Helpers\KeranjangStatus;
use App\Models\KeranjangDetail;
use Livewire\Component;

class Keranjangsaya extends Component
{


    public function render()
    {
        $keranjangAktif = KeranjangDetail::getKeranjangByStatus(KeranjangStatus::AKTIF);
        $keranjangCheckout = KeranjangDetail::getKeranjangByStatus(KeranjangStatus::CHECKOUT);
        $keranjangDibatalkan = KeranjangDetail::getKeranjangByStatus(KeranjangStatus::DIBATALKAN);

        return view('livewire.keranjangsaya', ['keranjang_aktif' => $keranjangAktif, 'keranjang_checkout' => $keranjangCheckout, 'keranjang_dibatalkan' => $keranjangDibatalkan]);
    }
}
