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
        'bukti_transfer'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function transfer()
    {
        return $this->belongsTo(Image::class, 'bukti_transfer');
    }
}
