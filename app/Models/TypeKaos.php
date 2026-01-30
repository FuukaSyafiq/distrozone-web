<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeKaos extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = true;
    public $timestamps = false;
    protected $table = "type_kaos";
    protected $keyType = 'int';
    protected $fillable = [
        'type',
    ];

    public function kaos()
    {
        return $this->hasMany(Kaos::class, 'merek_id');
    }
}
