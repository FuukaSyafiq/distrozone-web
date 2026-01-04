<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\HasName;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements HasName, MustVerifyEmail
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
        'nama',
        'password',
        'email',
        'role_id',
        'nik_verified',
        'nik',
        'foto_id',
        "password",
        "no_telepon",
        "alamat",
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

    public function getFilamentName(): string
    {
        // Return the desired attribute, with a fallback (e.g., to 'username' or a default string)
        return $this->nama;
    }

   
    public static function getUserByName($name)
    {
        return self::where('nama', $name)->first();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'foto_id');
    }
}
