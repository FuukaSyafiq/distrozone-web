<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaosVariant extends Model
{
    use HasFactory;

    public $incrementing = true;
    protected $table = "kaos_varian";
    protected $keyType = 'int';
    protected $fillable = [
        'kaos_id',
        'warna_id',
        'ukuran_id',
        'harga_jual',
        'harga_pokok',
        'stok_kaos',
        'image_path'
    ];

    public static function getKaosVarian(string $namaKaos)
    {
        return KaosVariant::with([
            'kaos',
            'warna',
            'ukuran',
        ])
            ->whereHas('kaos', function ($query) use ($namaKaos) {
                $query->where('nama_kaos', 'ILIKE', "%{$namaKaos}%");
            })
            ->first();
    }


    /* ================= RELATIONS ================= */

    public function kaos()
    {
        return $this->belongsTo(Kaos::class, 'kaos_id');
    }

    public function warna()
    {
        return $this->belongsTo(Warna::class, 'warna_id');
    }

    public function ukuran()
    {
        return $this->belongsTo(UkuranKaos::class, 'ukuran_id');
    }
}
