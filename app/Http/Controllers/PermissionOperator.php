<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionOperator extends Controller
{
    public function show()
    {
        return view("/permissionManager/pmlist_operator");
    }

    public function update(Request $request)
    {
        $role = $request->input('role');
        $module = $request->input('module');
        $action = $request->input('action');
        $roleId = Role::getIdByRole($role);
        Permission::allowPermission($roleId, $module, $action);

        return response()->json(['message' => 'Permission already allowed.'], 200);
    }

    public function delete(Request $request)
    {
        $role = $request->input('role');
        $module = $request->input('module');
        $action = $request->input('action');

        $roleId = Role::getIdByRole($role);
        Permission::deletePermission($roleId, $module, $action);

        return response()->json(['message' => 'Permission already deleted.'], 200);
    }
}
