<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWaVerified
{
    public function handle(Request $request, Closure $next): Response
{
    if (auth()->check()) {
        $user = auth()->user();

        // 1. Admin lolos
        if (isset($user->role) && $user->role === 'admin') {
            return $next($request);
        }

        // 2. CEK TAHAP 1: Profil Bisnis
        if (empty($user->whatsapp_number)) {
            if (!$request->routeIs('onboarding.profile.*', 'logout')) {
                return redirect()->route('onboarding.profile.form');
            }
            return $next($request);
        }

        // 3. CEK TAHAP 2: Status Verifikasi (KITA UBAH DI SINI)
        // Jika nilainya bukan 1 (masih 0 atau null)
        if ($user->is_wa_verified != 1) { 
            if (!$request->routeIs('onboarding.otp.*', 'logout')) {
                return redirect()->route('onboarding.otp.form');
            }
            return $next($request);
        }
        
        // 4. Jika sudah verified dan mencoba akses halaman onboarding manual
        if ($request->routeIs('onboarding.*')) {
            return redirect()->route('dashboard');
        }
    }

    return $next($request);
}
}