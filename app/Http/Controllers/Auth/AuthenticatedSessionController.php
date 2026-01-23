<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OtpMail;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate(); 
        $user = $request->user();

        if ($user->status == UserStatus::BANNED) {
            $this->dispatch('toast', message: 'Akun Anda telah diblokir secara permanen. Silakan hubungi administrator.');
        }

        if ($user->status == UserStatus::SUSPENDED) {
            $this->dispatch('toast', message: 'Akun Anda sedang ditangguhkan sementara. Silakan coba lagi nanti.');
        }

        if ($user->status != UserStatus::ACTIVE) {
            auth()->logout();

            return redirect('/login')->withErrors([
                'email' => 'Akun Anda tidak aktif.'
            ]);
        }
        $request->session()->regenerate();
        if (in_array($user->role_id, [
            Role::getIdByRole('ADMIN'),
            Role::getIdByRole('KASIR')
        ])) {
            $otp = random_int(100000, 999999);

            $user->update([
                'otp_code' => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(5),
                'otp_verified' => false,
            ]);

            Mail::to($user->email)->send(new OtpMail($otp, $user));

            return redirect()->route('otp.verify');
        }
       

        return redirect()->to('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
