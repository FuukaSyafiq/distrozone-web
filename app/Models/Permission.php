<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PermissionName;

class Permission extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'permission_name_id'
    ];

    protected $table = "permission";

    public static function deletePermission($roleId, $module, $action)
    {
        $permissionId = PermissionName::getIdByPermissionNameAndAction($module, $action);
        return self::where('role_id', $roleId)->where('permission_name_id', $permissionId)->delete();
    }

    public static function allowPermission($roleId, $module, $action)
    {
        $permissionId = PermissionName::getIdByPermissionNameAndAction($module, $action);
        return self::create(['role_id' => $roleId, 'permission_name_id' => $permissionId]);
    }

    public static function isAllowed($role, $module, $action): bool
    {
        $roleId = Role::getIdByRole($role);
        $permissionId = PermissionName::getIdByPermissionNameAndAction($module, $action);
        if (is_null($permissionId)) {
            return false;
        }

        // Mencari apakah ada permission berdasarkan role_id dan permission_name_id
        $permission = self::select('permission_name_id')->where('role_id', $roleId)
            ->where('permission_name_id', $permissionId)
            ->first('permission_name_id');


        // Jika ditemukan, kembalikan true, jika tidak ditemukan, kembalikan falsa
        return isset($permission) ? true : false;
    }

    public static function getPermissionByUserAndPermissionAndAction($module, $action)
    {
        $roleId = auth()->user()->role_id;

        $permissions = Permission::select(
            'permission_name.action'
        )
            ->join('roles', 'permission.role_id', '=', 'roles.id')
            ->join('permission_name', 'permission.permission_name_id', '=', 'permission_name.id')
            ->where('roles.id', $roleId)
            ->where('permission_name.name', $module)
            ->where('permission_name.action', $action)
            ->first();

        return $permissions;
    }
}
