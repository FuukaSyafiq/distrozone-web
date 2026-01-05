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
        'merek_kaos',
        'type_kaos',
        'warna_kaos',
        'ukuran',
        'description',
        'harga_jual',
        'harga_pokok',
        'stok_kaos',
    ];

    public static function getAllKaos()
    {
        return Kaos::with('image')->with('reviews')->get();
    }

    public static function getKaosById(string $id) {

    }

    public static function getAllKaosWithName($q)
    {
        return Kaos::with('image')->with('reviews')->orWhere('nama_kaos', 'ILIKE', '%' . $q . '%')->orWhere('merek_kaos', 'ILIKE', "%{$q}%")
            ->orWhere('type_kaos', 'ILIKE', "%{$q}%")->get();
      
    }
    public static function getKaosByName($name)
    {
        return self::where('nama_kaos', 'like', "%{$name}%")->first();
    }

    public function image()
    {
        return $this->hasMany(Image::class, 'id_kaos');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_kaos');
    }

    public function keranjangdetail()
    {
        return $this->hasMany(KeranjangDetail::class, 'id_kaos');
    }
}
