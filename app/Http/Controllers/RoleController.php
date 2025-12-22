<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string|max:255',
        ]);

        print_r($request->get("role"));
        Role::createRole($request->get("role"));

        return redirect()->back()->with('success', 'Role berhasil ditambahkan!');
    }

    public function delete(Request $request)
    {
        $role = $request->input("role");

        Role::deleteRole($role);
        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus!'
        ], 200);
        // return redirect()->back()->with('success', 'Role berhasil dihapus!');
    }
}
