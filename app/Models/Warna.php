<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warna extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = true;
    public $timestamps = false;
    protected $table = "warna";
    protected $keyType = 'int';
    protected $fillable = [
        'key',
        'label',
        'hex',
    ];

    public static function getWarna($key)
    {
        return self::where('key', $key)->first();
    }

    public function variants()
    {
        return $this->hasMany(KaosVariant::class, 'id');
    }
}
