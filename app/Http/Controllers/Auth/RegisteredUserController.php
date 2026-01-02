<?php



namespace App\Http\Controllers\Auth;

use App\Helpers\Status;
use App\Http\Controllers\ImageController;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $user = User::create([
            "email" => $request->email,
            "role_id" => Role::getIdByRole("CUSTOMER"),
            "verified" => false,
            "status" => Status::ACTIVE,
            "password" => Hash::make($request->password)
        ]);
        $nama = explode('@', $request->email)[0];

        $user->update([
            'nama' => $nama . Str::random(5)
        ]);

        event(new Registered($user));
        auth()->login($user);
        return redirect()->route('verification.notice');
    }
}
