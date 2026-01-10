<?php

namespace App\Livewire;

use App\Models\Kaos;
use Livewire\Component;

class PurchaseBox extends Component
{
    public $quantity = 1;
    public function render()
    {
        return view('livewire.purchase-box', ['quantity' => $this->quantity]);
    }
    public Kaos $kaos;

    public function mount(Kaos $kaos)
    {
        $this->kaos = $kaos;
    }

    public function check()
    {
        if (!auth()->check()) {
            $this->dispatch('toast', message: "Login diperlukan untuk menambahkan produk ke keranjang.");
            return;
        }

        return redirect()->to("/checkout?kaos={$this->kaos->id_kaos}&quantity={$this->quantity}");
    }
}
