<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use App\Models\Transaksi;
use Livewire\Attributes\On;
use Livewire\Component;

class PaymentInstruction extends Component
{
    public $method = null;

    #[On('set-transaksi-id')]
    public function updateInstruction($id)
    {
        // 1. Cari transaksi untuk mendapatkan nama bank/metode yang dipilih
        $transaksi = Transaksi::find($id);

        if ($transaksi) {
            // 2. Cari detail instruksi di tabel payment_methods berdasarkan nama bank
            // Sesuaikan kolom 'nama_bank' dengan kolom di tabel transaksi kamu
            $this->method = PaymentMethod::where('nama_bank', $transaksi->metode_pembayaran)->first();
        }
    }

    public function render()
    {
        return view('livewire.payment-instruction');
    }
}
