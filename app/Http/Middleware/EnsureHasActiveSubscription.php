<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\PaymentController; // <-- WAJIB TAMBAHKAN INI
use App\Models\Subscription;

class EnsureHasActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // 1. Cek apakah ada paket yang BENAR-BENAR aktif (tanggal belum lewat)
            $activeSub = Subscription::where('user_id', $user->id)
                            ->where('status', 'active')
                            ->where('ends_at', '>', now())
                            ->first();

            // JIKA USER TIDAK PUNYA PAKET AKTIF
            if (!$activeSub) {
                
                // 2. Cek apakah dia pernah langganan sebelumnya (User Lama)
                $lastSub = Subscription::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->first();

                if ($lastSub) {
                    // --- KONDISI A: USER LAMA YANG PAKETNYA HABIS (EXPIRED) ---
                    
                    // Update status user jadi expired jika masih nyangkut di 'active'
                    if ($user->subscription_status === 'active') {
                        $user->update(['subscription_status' => 'expired']);
                    }

                    // Buatkan tagihan perpanjangan otomatis
                    PaymentController::generateRenewalInvoice($user);

                    // Lempar ke halaman tagihan
                    return redirect()->route('user.invoice.index')
                        ->with('error', 'Masa aktif layanan Anda telah habis. Invoice perpanjangan otomatis diterbitkan, silakan selesaikan pembayaran.');
                
                } else {
                    // --- KONDISI B: USER BARU YANG BELUM PERNAH LANGGANAN ---
                    
                    // Lempar ke halaman pemilihan paket seperti kode asli Anda
                    return redirect()->route('user.plans.index') 
                        ->with('error', 'Silakan pilih dan berlangganan paket terlebih dahulu untuk mengakses fitur premium ini.');
                }
            }
        }

        return $next($request);
    }
}