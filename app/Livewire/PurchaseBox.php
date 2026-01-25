<?php

namespace App\Livewire;

use App\Models\Kaos;
use App\Models\KaosVariant;
use Livewire\Component;

class PurchaseBox extends Component
{
    public $quantity = 1;

    protected $listeners = ['variantChanged' => 'updateVariant'];
    public function render()
    {
        return view('livewire.purchase-box', ['quantity' => $this->quantity]);
    }
    public KaosVariant $variant;
    public Kaos $kaos;

    public function mount(int $variantId, Kaos $kaos)
    {
        $this->kaos = $kaos;
        $this->variant = KaosVariant::findOrFail($variantId);
    }

    public function updateVariant(int $variantId)
    {
        // Ambil variant baru
        $this->variant = KaosVariant::findOrFail($variantId);
    }

    public function check()
    {
        if (!auth()->check()) {
            $this->dispatch('toast', message: "Login diperlukan untuk menambahkan produk ke keranjang.");
            return redirect("/login");
        }

        // return redirect()->to("/checkout?kaos={$this->kaos->id_kaos}&quantity={$this->quantity}");
    }
}
