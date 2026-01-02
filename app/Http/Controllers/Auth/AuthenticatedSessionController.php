<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        if ($request->user()->status == Status::BANNED){
            $this->dispatch('toast', message: 'Akun Anda telah diblokir secara permanen. Silakan hubungi administrator.');
        }

        if ($request->user()->status == Status::SUSPENDED) {
            $this->dispatch('toast', message: 'Akun Anda sedang ditangguhkan sementara. Silakan coba lagi nanti.');
        }

        if ($request->user()->status != Status::ACTIVE) {
            $this->dispatch('toast', message: 'Akun Anda belum diverifikasi. Kami akan mengirimkan email setelah proses verifikasi selesai.');
        }
        $request->session()->regenerate();

        if ($request->user()->role_id == Role::getIdByRole("ADMIN")) {
            return redirect('/admin');
        } else if ($request->user()->role_id == Role::getIdByRole("KASIR")) {
            return redirect('/kasir');
        }
        
        return redirect('/');
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
