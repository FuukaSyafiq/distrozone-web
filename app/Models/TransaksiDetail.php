<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;
    protected $table = "transaksi_detail";
    protected $primaryKey = 'id_detail_transaksi';
    protected $fillable = [
        'id_transaksi',
        'id_kaos_varian',
        'qty',
        'harga_satuan',
        'harga_pokok',
        'subtotal',
    ];

    public function kaos_varian()
    {
        return $this->belongsTo(KaosVariant::class, 'id_kaos_varian');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

}
