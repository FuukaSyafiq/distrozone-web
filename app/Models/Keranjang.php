<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';

    protected $primaryKey = 'id_keranjang';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'status',
        'id_customer'
    ];

    public function customer() {
        return $this->belongsTo(User::class, 'id_customer');
    }

    public static function getKeranjangByCustomerId($customerId) {
        return self::where('id_customer', $customerId)->first();
    }

}
