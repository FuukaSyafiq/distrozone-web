<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ongkir extends Model
{
    use HasFactory;

    protected $table = 'ongkir';
    public $incrementing = true;
    protected $fillable = [
        'wilayah',
        'tarif_per_kg'
    ];

    public static function getOngkirByWilayah($wilayah) {
        return self::where('wilayah', 'ilike', "%{$wilayah}")->first();
    }
}
