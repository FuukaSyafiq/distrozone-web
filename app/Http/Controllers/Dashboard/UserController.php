<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use \App\Models\Dashboard;
use App\Models\DataPendaftar;

class UserController extends Controller
{
    public function create(): View
    {
        $user_data = DataPendaftar::get();
        $user = auth()->user();

        return view('dashboard', ['user_data' => $user_data, 'user' => $user]);
    }
}
