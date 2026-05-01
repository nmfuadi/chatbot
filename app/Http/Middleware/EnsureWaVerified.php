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

            // 1. Jika Admin, langsung lolos ke Dashboard
            if (isset($user->role) && $user->role === 'admin') {
                return $next($request);
            }

            // 2. CEK TAHAP 1: Apakah Profil Bisnis sudah diisi?
            // Kita asumsikan profil diisi jika 'whatsapp_number' sudah ada nilainya
            if (empty($user->whatsapp_number)) {
                // Jika belum isi profil, dan bukan sedang di halaman profil, arahkan ke sana
                if (!$request->routeIs('onboarding.profile.*', 'logout')) {
                    return redirect()->route('onboarding.profile.form')
                        ->with('info', 'Harap lengkapi profil bisnis Anda terlebih dahulu.');
                }
                return $next($request);
            }

            // 3. CEK TAHAP 2: Jika profil sudah ada, apakah WA sudah diverifikasi?
            if (is_null($user->whatsapp_verified_at)) {
                // Jika belum verifikasi, dan bukan sedang di halaman OTP, arahkan ke sana
                if (!$request->routeIs('onboarding.otp.*', 'logout')) {
                    return redirect()->route('onboarding.otp.form')
                        ->with('info', 'Nomor WA tersimpan, silakan lakukan verifikasi OTP.');
                }
                return $next($request);
            }
            
            // 4. TAHAP FINAL: Jika profil sudah ada DAN WA sudah verifikasi
            // Jika dia mencoba mengakses halaman onboarding secara manual, buang ke Dashboard
            if ($request->routeIs('onboarding.*')) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}