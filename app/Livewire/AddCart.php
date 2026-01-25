<?php

namespace App\Livewire;

use App\Helpers\CartStatus;
use App\Helpers\KeranjangStatus;
use App\Models\Kaos;
use App\Models\KaosVariant;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use Livewire\Component;
use Livewire\Attributes\Reactive;

class AddCart extends Component
{
    public Kaos $kaos;
    public KaosVariant $variant;

    public $quantity = 1;

    public function mount(KaosVariant $variant, int $quantity)
    {
        $this->variant = $variant;
        $this->kaos = $variant->kaos;
        $this->quantity = $quantity;
    }

    public function updatedQuantity($value)
    {
    }

    public function render()
    {
        return view('livewire.add-cart');
    }

    public function add()
    {
        $user = auth()->user();

        if (!auth()->check()) {
            return redirect("/login");
        }

        $keranjang = Keranjang::where('status', KeranjangStatus::AKTIF)->where('id_customer', $user->id_user)->first();
        // dd($this->quantity);
        if (!$keranjang) {
            $keranjang = Keranjang::create([
                'status' => CartStatus::AKTIF,
                'id_customer' => $user->id_user
            ]);
        }

        KeranjangDetail::create([
            'id_keranjang' => $keranjang->id_keranjang,
            'id_kaos_varian' => $this->variant->id,
            'harga_satuan' => $this->variant->harga_jual,
            'qty' => $this->quantity,
            'subtotal' => $this->variant->harga_jual * $this->quantity
        ]);

        KaosVariant::where('id', $this->variant->id)->decrement('stok_kaos', $this->quantity);

        $this->dispatch('toast', message: 'Berhasil ditambahkan ke keranjang');
    }
}
