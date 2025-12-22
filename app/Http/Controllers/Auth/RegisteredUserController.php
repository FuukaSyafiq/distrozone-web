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
        // dd($request);
        DB::beginTransaction();
        $ktp = (new ImageController())->store($request, "ktp");
        try {
           

            User::create([
                "name" => $request->name,
                "email" => $request->email,
                "role_id" => Role::getIdByRole("PENYEWA"),
                "contact" => $request->contact,
                "address" => $request->address,
                "ktp_id" => $ktp->id,
                "password" => Hash::make($request->password)
            ]);
            DB::commit();

            return redirect()->to('/login');
        } catch (\Exception $exception) {
            DeleteFromStorage($ktp->file_name);
            DB::rollBack();
          throw $exception;
        }
    }
}
