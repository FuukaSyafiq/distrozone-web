<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_method';
    public $incrementing = true;
    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'nama_penerima',
        'logo',
        'instruksi',
        'is_active'
    ];
}
