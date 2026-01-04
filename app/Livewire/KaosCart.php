<?php

namespace App\Livewire;

use App\Models\KeranjangDetail;
use Livewire\Component;

class KaosCart extends Component
{

    public KeranjangDetail $keranjang;
    public int $subtotal;
    public int $qty;
    public $selected = [];


    public function mount(KeranjangDetail $keranjang)
    {
        $this->keranjang = $keranjang;
        $this->subtotal = $keranjang->subtotal;
        $this->qty = $keranjang->qty;
    }
    public function incrementQuantity($keranjangId)
    {
        $keranjang = KeranjangDetail::find($keranjangId);

        if ($keranjang) {
            // Check stock availability (optional)
            if ($keranjang->kaos->stok && $keranjang->qty >= $keranjang->kaos->stok) {
                session()->flash('error', 'Cannot add more items. Stock limit reached.');
                return;
            }

            $keranjang->qty += 1;
            $keranjang->subtotal = $keranjang->qty * $keranjang->harga_satuan;
            $keranjang->save();
        }
    }

    public function decrementQuantity($keranjangId)
    {
        $keranjang = KeranjangDetail::find($keranjangId);

        if ($keranjang) {
            // Prevent quantity from going below 1
            if ($keranjang->qty > 1) {
                $keranjang->qty -= 1;
                $keranjang->subtotal = $keranjang->qty * $keranjang->harga_satuan;
                $keranjang->save();
            } else {
                session()->flash('error', 'Minimum quantity is 1. Use remove button to delete item.');
            }
        }
    }

    public function removeItem($keranjangId)
    {
        $keranjang = KeranjangDetail::where('id_keranjang_detail', $keranjangId)->first();

        if ($keranjang) {
            $keranjang->delete();
            session()->flash('success', 'Item removed from cart.');
        }
    }


    public function render()
    {
        return view('livewire.kaos-cart');
    }
}
