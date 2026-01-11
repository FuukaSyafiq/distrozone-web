<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Keranjang;
use App\Models\Kaos;

class KeranjangDetail extends Model
{
    use HasFactory;

    protected $table = 'keranjang_detail';

    protected $primaryKey = 'id_keranjang_detail';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_kaos',
        'id_keranjang',
        'qty',
        'harga_satuan',
        'subtotal'
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'id_keranjang');
    }

    public function kaos()
    {
        return $this->belongsTo(Kaos::class, 'id_kaos');
    }

    public static function getAllKeranjang() {
        return self::with('kaos')->with('keranjang')->get();
    }

    public static function getKeranjangByStatus($status) {
        return self::with(['kaos','kaos.image', 'keranjang'])
            ->whereHas('keranjang', function ($q) use ($status) {
                $q->where('status', $status)
                    ->where('id_customer', auth()->user()->id_user);
            })
            ->get();
    }
}
