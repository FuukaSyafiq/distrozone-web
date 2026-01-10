<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    public $incrementing = true;
    public $timestamps = false;
    protected $table = "kota";
    protected $keyType = 'int';
    protected $fillable = [
        'kota',
        'provinsi_id'
    ];

    public static function getKota($kota)
    {
        return self::where('kota', $kota)->first();
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
}
