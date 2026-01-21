<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;

    protected $table = 'pendapatan';

    protected $fillable = [
        'tanggal',
        'transaksi_id',
        'jumlah',
        'jenis', // ONLINE / OFFLINE
    ];

    protected $appends = ['modal', 'keuntungan'];

    // =====================
    // RELATION
    // =====================
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    // =====================
    // ACCESSOR
    // =====================

    /**
     * Modal = total HPP barang terjual
     */
    public function getModalAttribute()
    {
        if (!$this->transaksi) {
            return 0;
        }

        return $this->transaksi->details
            ->sum(fn($d) => $d->qty * $d->harga_pokok);
    }

    /**
     * Keuntungan = omset - modal - ongkir
     */
    public function getKeuntunganAttribute()
    {
        $ongkir = $this->transaksi->ongkir;

        return $this->jumlah - $this->modal - $ongkir;
    }
}
