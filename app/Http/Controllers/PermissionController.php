<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function show($role)
    {
        return view("permissionManager.pmlist", ['role' => $role]);
    }

    public function update(Request $request)
    {
        // print_r($name);
        $role = $request->input("role");
        $module = $request->input('module');
        $action = $request->input('action');
        $roleId = Role::getIdByRole($role);
        Permission::allowPermission($roleId, $module, $action);

        return response()->json(['message' => 'Permission is allowed.'], 200);
    }

    public function delete(Request $request)
    {
        $role = $request->input("role");
        $module = $request->input('module');
        $action = $request->input('action');

        $roleId = Role::getIdByRole($role);

        Permission::deletePermission($roleId, $module, $action);
        return response()->json(['message' => 'Permission is deleted.'], 200);
    }
}
