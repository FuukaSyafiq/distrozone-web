<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Filament\Models\Contracts\HasAvatar;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\HasName;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements HasName, MustVerifyEmail,HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use SoftDeletes;
    protected $primaryKey = 'id_user';
    public $incrementing = true;

    public function getFilamentAvatarUrl(): ?string
    {
        // Jika kolom foto_user kosong, return null (nanti muncul inisial nama)
        if (!$this->foto_user) {
            return null;
        }

        // Karena kamu pakai MinIO/S3, ambil URL dari disk s3
        return Storage::disk('s3')->url($this->foto_user);
    }

    protected $keyType = 'int';
    protected $fillable = [
        'nama',
        'password',
        'email',
        'role_id',
        'nik_verified',
        'nik',
        'foto_user',
        "password",
        "no_telepon",
        "alamat_lengkap",
        "kota_id",
        "status",
        "otp_code",
        "otp_expires_at",
        "otp_verified",
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
    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }
   
}
