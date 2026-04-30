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

        // 1. Pastikan user sudah login
        if (!$user) {
            return redirect()->route('login');
        }

        // ==========================================
        // 2. JALUR VIP: BYPASS UNTUK ADMIN
        // ==========================================
        // Sesuaikan pengecekan ini dengan struktur tabel kamu.
        // Jika pakai kolom 'role', gunakan: $user->role === 'admin'
        // Jika pakai email, gunakan: $user->email === 'admin@domain.com'
        
        if (isset($user->role) && $user->role === 'admin') {
            return $next($request); // Langsung masuk, lewati semua pengecekan di bawah!
        }
        // ==========================================

        // 3. Cek apakah user (merchant biasa) sudah mengisi profil
        if (empty($user->whatsapp_number)) {
            if (!$request->routeIs('onboarding.profile.*')) {
                return redirect()->route('onboarding.profile.form')
                    ->with('error', 'Akses ditolak! Silakan lengkapi profil bisnis Anda terlebih dahulu.');
            }
        } 
        // 4. Cek apakah user (merchant biasa) sudah verifikasi OTP
        elseif (!$user->is_wa_verified) {
            if (!$request->routeIs('onboarding.otp.*') && !$request->routeIs('onboarding.profile.*')) {
                return redirect()->route('onboarding.otp.form')
                    ->with('error', 'Akses ditolak! Silakan verifikasi nomor WhatsApp Anda terlebih dahulu.');
            }
        }

        // 5. CEK LANGGANAN & PEMBAYARAN
$subscription = \App\Models\Subscription::where('user_id', $user->id)->latest()->first();

if (!$subscription) {
    if (!$request->routeIs('user.plans.*')) {
        return redirect()->route('user.plans.index');
    }
} else {
    // Jika punya subscription tapi statusnya 'pending' (berbayar tapi belum lunas)
    if ($subscription->status === 'pending') {
        $invoice = \App\Models\Invoice::where('subscription_id', $subscription->id)->first();
        
        // Paksa ke halaman invoice jika tidak sedang di halaman invoice/payment
        if (!$request->routeIs('user.invoice.*') && !$request->routeIs('payment.*')) {
            return redirect()->route('user.invoice.show', $invoice->id)
                ->with('error', 'Silakan selesaikan pembayaran invoice Anda.');
        }
    }
}



        // Jika semua aman, silakan masuk!
        return $next($request);
    }
}