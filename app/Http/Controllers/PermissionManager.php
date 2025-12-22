<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionManager extends Controller
{
    public function store() {

        return view('/permissionManager/pmlist_admin');
    }
}
