<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    use HasFactory;

    protected $table = "jam_operasional";

    protected $fillable = [
        'hari',
        'jam_buka',
        'jam_tutup',
        'status'
    ];
}
