<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public static function findAllUsers()
    {
        return self::select(['name', 'nomor_induk_kependudukan', 'email', 'status_nikah', 'kelamin', 'tempat_lahir', 'kewarganegaraan', 'alamat', 'nomor_telepon'])->where(['role_id' => '1'])->get()->toArray();
    }
}
