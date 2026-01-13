<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Keranjang;
use App\Models\Kaos;
use Illuminate\Support\Facades\Auth;

class KeranjangDetail extends Model
{
    use HasFactory;

    protected $table = 'keranjang_detail';

    protected $primaryKey = 'id_keranjang_detail';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_kaos_varian',
        'id_keranjang',
        'qty',
        'harga_satuan',
        'subtotal'
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'id_keranjang');
    }

    public function kaos_varian()
    {
        return $this->belongsTo(KaosVariant::class, 'id_kaos_varian');
    }

    public static function getKeranjangUserLogin()
    {
        $customerId = Auth::id();

        return self::with('kaos_varian', 'keranjang')
            ->whereHas('keranjang', function ($q) use ($customerId) {
                $q->where('id_customer', $customerId);
            })
            ->get();
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
