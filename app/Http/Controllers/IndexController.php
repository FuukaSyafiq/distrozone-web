<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\TipeRoom;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    public function gets()
    {
  
        // dd($rooms);
        return view('index', ['user' => auth()->user()]);
        // return view('auth.verify-email');
        // return redirect()->route('verification.notice');
    }
    public function store(Request $request) {

        return redirect()->to("/");
    }
}
