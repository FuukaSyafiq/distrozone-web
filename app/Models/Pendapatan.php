<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $table = "pendapatan";
    protected $keyType = 'int';

    protected $fillable = [
        'qty',
        'nama_kaos',
        'total_harga_jual',
        'total_harga_pokok',
        'ongkir'
    ];
}
