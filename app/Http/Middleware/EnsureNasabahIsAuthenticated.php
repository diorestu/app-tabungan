<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureNasabahIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('nasabah')->check()) {
            return redirect()->route('nasabah.login')->with('error', 'Silakan login dengan ID Nasabah dan Nomor HP terlebih dahulu.');
        }

        $nasabah = Auth::guard('nasabah')->user();
        if ($nasabah->status !== 'aktif') {
            Auth::guard('nasabah')->logout();
            return redirect()->route('nasabah.login')->with('error', 'Akun nasabah Anda berstatus non-aktif. Silakan hubungi petugas.');
        }

        return $next($request);
    }
}
