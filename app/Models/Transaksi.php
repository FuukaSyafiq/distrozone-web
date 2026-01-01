<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = "transaksi";
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'kode_transaksi',
        'id_kasir',
        'id_customer',
        'jenis_transaksi',
        'metode_pembayaran',
        'total_harga',
        'ongkir',
        'id_ongkir',
        'status',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'id_customer');
    }

    public function ongkir()
    {
        return $this->belongsTo(Ongkir::class, 'id_ongkir');
    }
}
