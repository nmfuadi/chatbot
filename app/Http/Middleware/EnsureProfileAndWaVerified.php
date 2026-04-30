<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileAndWaVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Pastikan user sudah login (jaga-jaga)
        if (!$user) {
            return redirect()->route('login');
        }

        // 2. Cek apakah user sudah mengisi profil (kita cek dari nomor WA-nya)
        if (empty($user->whatsapp_number)) {
            // Jika rute saat ini bukan halaman profil, tendang ke halaman profil
            if (!$request->routeIs('onboarding.profile.*')) {
                return redirect()->route('onboarding.profile.form')
                    ->with('error', 'Akses ditolak! Silakan lengkapi profil bisnis Anda terlebih dahulu.');
            }
        } 
        // 3. Cek apakah user sudah verifikasi OTP
        elseif (!$user->is_wa_verified) {
            // Jika rute saat ini bukan halaman OTP atau Profil, tendang ke halaman OTP
            if (!$request->routeIs('onboarding.otp.*') && !$request->routeIs('onboarding.profile.*')) {
                return redirect()->route('onboarding.otp.form')
                    ->with('error', 'Akses ditolak! Silakan verifikasi nomor WhatsApp Anda terlebih dahulu.');
            }
        }

        // Jika semua aman, silakan masuk!
        return $next($request);
    }
}