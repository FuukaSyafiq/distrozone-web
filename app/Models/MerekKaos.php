<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerekKaos extends Model
{
    use HasFactory;

    public $incrementing = true;
    public $timestamps = false;
    protected $table = "merek_kaos";
    protected $keyType = 'int';
    protected $fillable = [
        'merek'
    ];
}
