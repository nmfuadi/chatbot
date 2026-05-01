<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWaVerified
{
    public function handle(Request $request, Closure $next): Response
{
    // Jika user sudah login TAPI belum verifikasi WA
    if (auth()->check() && is_null(auth()->user()->whatsapp_verified_at)) {
        
        // Pengecualian: Biarkan mereka mengakses profil awal dan form OTP
        if (!$request->routeIs('onboarding.profile.*', 'onboarding.otp.*')) {
            return redirect()->route('onboarding.otp.form')
                ->with('error', 'Anda harus memverifikasi nomor WhatsApp terlebih dahulu.');
        }
    }

    return $next($request);
}
}