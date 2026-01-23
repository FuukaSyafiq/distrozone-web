<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function show()
    {
        return view('otp.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        // Cek apakah OTP sudah expired
        if (now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors([
                'otp' => 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.'
            ]);
        }

        // Verifikasi OTP
        if (Hash::check($request->otp, $user->otp_code)) {
            $user->update([
                'otp_verified' => true,
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);
            if (
                $user->role_id == \App\Models\Role::getIdByRole('ADMIN')
            ) {
                return redirect("/admin");
            } else if (
                $user->role_id == \App\Models\Role::getIdByRole('KASIR')
            ) {
                return redirect("/kasir");
            }
        }

        return back()->withErrors([
            'otp' => 'Kode OTP tidak valid.'
        ]);
    }

    public function resend()
    {
        $user = Auth::user();

        $otp = random_int(100000, 999999);

        $user->update([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
            'otp_verified' => false,
        ]);

        Mail::to($user->email)->send(new OtpMail($otp, $user));

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
