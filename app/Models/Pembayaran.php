<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = "pembayaran";
    protected $primaryKey = 'id_pembayaran';
    protected $fillable = [
        'id_transaksi',
        'status',
        'no_invoice',
        'bukti_transfer',
        'catatan'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }
}
