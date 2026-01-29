<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Tambahkan ini
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request; // Ganti EmailVerificationRequest jadi Request biasa
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // 1. Ambil user berdasarkan ID dari URL
        $user = User::findOrFail($request->route('id'));

        // 2. Validasi Hash (Keamanan: Memastikan link tidak dimanipulasi)
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        // 3. Jika user sudah pernah verifikasi sebelumnya
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        // 4. Proses Verifikasi
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
