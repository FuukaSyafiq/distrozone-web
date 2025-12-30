<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;
    protected $table = "transaksi_detail";

    protected $fillable = [
        'id_transaksi',
        'id_kaos',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    public function kaos()
    {
        return $this->belongsTo(Kaos::class, 'id_kaos');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }
}
