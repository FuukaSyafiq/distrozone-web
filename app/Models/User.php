<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Agama;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\DataPendaftar;
use App\Models\GolonganDarah;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $primaryKey = 'id_user';
    public $incrementing = true;

    protected $keyType = 'int';
    protected $fillable = [
        'name',
        'username',
        'password',
        'email',
        'role_id',
        "password",
        "contact",
        "address",
        "foto_id"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public static function getUserByUsername($username)
    {
        return self::where('username', $username)->first();
    }
    public static function getUserByName($name)
    {
        return self::where('nama', $name)->first();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
