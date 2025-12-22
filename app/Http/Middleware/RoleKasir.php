<?php

namespace App\Http\Middleware;

use App\Models\RentedRoom;
use App\Models\Role;
use App\Models\Room;
use App\Models\Tagihan;
use Carbon\Carbon;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleKasir
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && auth()->user()->role_id === Role::getIdByRole('KASIR')) {
            return $next($request);
        }
        return redirect()->to('/login');
    }
}
