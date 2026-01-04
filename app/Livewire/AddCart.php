<?php

namespace App\Livewire;

use App\Helpers\CartStatus;
use App\Models\Kaos;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use Livewire\Component;

class AddCart extends Component
{
    public Kaos $kaos;

    public function mount(Kaos $kaos)
    {
        $this->kaos = $kaos;
    }

    public function render()
    {
        return view('livewire.add-cart');
    }

    public function add()
    {
        $user = auth()->user();

        if (!auth()->check()) {
            $this->dispatch('toast', message: "Login diperlukan untuk menambahkan produk ke keranjang.");
            $this->dispatch('redirect-login');

            return;
        }

        $keranjang = Keranjang::getKeranjangByCustomerId($user->id_user);

        if (!$keranjang) {
            $keranjang = Keranjang::create([
                'status' => CartStatus::AKTIF,
                'id_customer' => $user->id_user
            ]);
        }

        KeranjangDetail::create([
            'id_keranjang' => $keranjang->id_keranjang,
            'id_kaos' => $this->kaos->id_kaos,
            'harga_satuan' => $this->kaos->harga_jual,
            'qty' => '1',
            'subtotal' => $this->kaos->harga_jual
        ]);
        $this->dispatch('toast', message: 'Berhasil ditambahkan ke keranjang');
    }
}
