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
        'harga_jual',
        'harga_pokok',
        'stok_kaos',
    ];


    public static function getKaosByName($name)
    {
        return self::where('nama_kaos', 'like', "%{$name}%")->first();
    }

    public function image()
    {
        return $this->hasMany(Image::class, 'id_kaos');
    }
}   
