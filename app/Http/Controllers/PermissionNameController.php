<?php

namespace App\Http\Controllers;
use App\Models\PermissionName;
use Illuminate\Http\Request;

class PermissionNameController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "name" => "string|max:50"
        ]);

        $name = $request->input('name');
        $data = [
            [
                "name" => $name,
                "action" => "CREATE",
            ],
            [
                "name" => $name,
                "action" => "READ",
            ],
            [
                "name" => $name,
                "action" => "UPDATE",
            ],
            [
                "name" => $name,
                "action" => "DELETE",
            ],
        ];

        PermissionName::insert($data);

        return redirect()->back()->with("success", "Permission $name already added");
    }


    public function delete(Request $request) {
          $request->validate([
            "name" => "string|max:50"
        ]);
        
        $name = $request->input('name');

        PermissionName::deleteNameAndAction($name);

        return response()->json(['message' => 'Permission name already deleted.'], 200);
    }
}
