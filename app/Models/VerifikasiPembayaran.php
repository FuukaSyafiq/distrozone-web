<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiPembayaran extends Model
{
    use HasFactory;

    protected $table = "pembayaran";

    protected $fillable = [
        "pengirim",
        "room",
        "no_invoice",
        "bukti_file",
        "is_valid",
        "tanggal_dibayar",
        "amount"
    ];
}
