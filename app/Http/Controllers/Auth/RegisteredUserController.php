<?php



namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ImageController;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Auth\RegisterRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();
        // dd($request);
        $user = User::create([
            "nama" => $request->nama,
            "email" => $request->email,
            "nik" => $request->nik,
            "username" => $request->username,
            "role_id" => Role::getIdByRole("CUSTOMER"),
            "no_telepon" => $request->no_telepon,
            "alamat" => $request->alamat,
            "password" => Hash::make($request->password)
        ]);

        return redirect('/login');
    }
}
