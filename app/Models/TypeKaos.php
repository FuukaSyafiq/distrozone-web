<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeKaos extends Model
{
    use HasFactory;

    public $incrementing = true;
    public $timestamps = false;
    protected $table = "type_kaos";
    protected $keyType = 'int';
    protected $fillable = [
        'type'
    ];
}
