<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaos extends Model
{
    use HasFactory;
    protected $table = 'kaos';

    protected $primaryKey = 'id_kaos';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nama_kaos',
        'merek_id',
        'type_id',
        'description',
        'harga_jual',
        'harga_pokok',
    ];

    public static function getAllKaos()
    {
        return Kaos::with([
            'variants' => function ($q) {
                $q->select('id', 'kaos_id', 'warna_id', 'image_path', 'stok_kaos', 'ukuran_id');
            },
            'variants.warna',
            'variants.ukuran',
        ])
            ->get();
    }

    public static function getKaosById(string $id)
    {
        return Kaos::with([
            'variants' => function ($q) {
                $q->select('id', 'kaos_id', 'warna_id', 'image_path', 'stok_kaos', 'ukuran_id');
            },
            'variants.warna',
            'variants.ukuran',
        ])
            ->where('id_kaos', $id) // mencari berdasarkan id_kaos
            ->first();
    }


    public static function searchKaos(string $q)
    {
        return Kaos::query()
            ->with([
                'merek',
                'type',
                'variants.warna',
                'variants.ukuran',
            ])
            ->where(function ($query) use ($q) {
                $query
                    ->where('nama_kaos', 'ILIKE', "%{$q}%")
                    ->orWhereHas('merek', function ($m) use ($q) {
                        $m->where('merek', 'ILIKE', "%{$q}%");
                    })
                    ->orWhereHas('type', function ($t) use ($q) {
                        $t->where('type', 'ILIKE', "%{$q}%");
                    })
                    ->orWhereHas('variants', function ($v) use ($q) {
                        $v->whereHas('warna', function ($w) use ($q) {
                            $w->where('label', 'ILIKE', "%{$q}%");
                        });
                    })
                    ->orWhereHas('variants', function ($v) use ($q) {
                        $v->whereHas('ukuran', function ($u) use ($q) {
                            $u->where('ukuran', 'ILIKE', "%{$q}%");
                        });
                    });
            })
            ->get();
    }


    public static function getKaosByName($name)
    {
        return self::where('nama_kaos', 'like', "%{$name}%")->first();
    }

    public function warna()
    {
        return $this->belongsTo(Warna::class, 'id_warna_kaos');
    }

    public function type()
    {
        return $this->belongsTo(TypeKaos::class, 'type_id');
    }

    public function merek()
    {
        return $this->belongsTo(MerekKaos::class, 'merek_id');
    }

    public function variants()
    {
        return $this->hasMany(KaosVariant::class, 'kaos_id');
    }

    public function keranjangdetail()
    {
        return $this->hasMany(KeranjangDetail::class, 'id_kaos');
    }
}
