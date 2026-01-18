<?php

namespace App\Livewire;

use App\Helpers\CartStatus;
use App\Helpers\KeranjangStatus;
use App\Models\Kaos;
use App\Models\KaosVariant;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BeliLangsung extends Component
{
    public Kaos $kaos;
    public KaosVariant $variant;

    #[Reactive]
    public $quantity = 1;

    public function mount(KaosVariant $variant, int $quantity)
    {
        $this->variant = $variant;
        $this->kaos = $variant->kaos;
        $this->quantity = $quantity;
    }

    public function render()
    {
        return view('livewire.beli-langsung');
    }

    public function add()
    {
        $user = auth()->user();

        if (!auth()->check()) {
            $this->dispatch('toast', message: "Login diperlukan untuk menambahkan produk ke keranjang.");
            return;
        }
        $keranjang = Keranjang::where('status', KeranjangStatus::AKTIF)->where('id_customer', $user->id_user)->first();
        // $keranjang = Keranjang::getKeranjangByCustomerId($user->id_user);

        if (!$keranjang) {
            $keranjang = Keranjang::create([
                'status' => CartStatus::AKTIF,
                'id_customer' => $user->id_user
            ]);
        }

        $keranjangDetail = KeranjangDetail::create([
            'id_keranjang' => $keranjang->id_keranjang,
            'id_kaos_varian' => $this->variant->id,
            'harga_satuan' => $this->kaos->harga_jual,
            'qty' => $this->quantity,
            'subtotal' => $this->kaos->harga_jual * $this->quantity
        ]);


        // return redirect()->route('checkout');
        return redirect()->route('checkout', [
            'keranjang' => $keranjang->id_keranjang
        ]);

        // return redirect()->to('checkout', [
        // 'keranjang' => $keranjangDetail,
        // 'keranjangUtama' => $keranjang,
        // ]);
    }
}
