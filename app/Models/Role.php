<?php

namespace App\Models;

use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        "role"
    ];

    public $timestamps = false;
    protected $table = "roles";

    public static function getIdByRole($role)
    {
        $user_role = self::where('role', $role)->firstOrFail();
        return $user_role->id;
    }

    public static function deleteRole($role)
    {
        return self::where("role", $role)->delete();
    }

    public static function getAllRole()
    {
        return self::get();
    }

    public static function getAllRoleExceptWargaAdmin()
    {
        return Role::where('role', '!=', 'WARGA')
            ->where('role', '!=', 'ADMIN')
            ->pluck('id');
    }


    public static function createRole($role)
    {
        return self::create(["role" => $role]);
    }

    public static function getRole($id)
    {
        return self::find($id);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    // public function AuthorityItem()
    // {
    //     return $this->hasMany(AuthorityItem::class);
    // }
}
