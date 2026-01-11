<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    public $incrementing = true;
    public $timestamps = false;
    protected $table = "provinsi";
    protected $keyType = 'int';
    protected $fillable = [
        'provinsi',
    ];

    public static function getProvinsi($provinsi) {
        return self::where('provinsi', $provinsi)->first();
    }

    
}
