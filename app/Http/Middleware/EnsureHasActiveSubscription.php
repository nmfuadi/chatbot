<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Subscription;
use App\Http\Controllers\PaymentController;

class EnsureHasActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // 1. Cek apakah ada paket yang BENAR-BENAR aktif (tanggal belum lewat & status active)
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
                    // --- KODE YANG ANDA TANYAKAN ADA DI SINI ---
                    
                    // Update status user jadi expired jika masih nyangkut di 'active'
                    if ($user->subscription_status === 'active') {
                        $user->update(['subscription_status' => 'expired']);
                    }

                    // Coba buatkan tagihan perpanjangan otomatis
                    $invoice = PaymentController::generateRenewalInvoice($user);

                    // JIKA INVOICE BERHASIL DIBUAT (Berarti ini paket berbayar)
                    if ($invoice) {
                        return redirect()->route('user.invoice.index')
                            ->with('error', 'Masa aktif/kuota Anda telah habis. Invoice perpanjangan telah diterbitkan, silakan selesaikan pembayaran.');
                    } 
                    
                    // JIKA INVOICE TIDAK DIBUAT (Berarti sebelumnya pakai paket gratis)
                    return redirect()->route('user.plans.index')
                        ->with('error', 'Kuota Paket Gratis Anda telah habis. Silakan upgrade ke paket Premium untuk melanjutkan penggunaan AI.');
                
                } else {
                    // --- KONDISI B: USER BARU YANG BELUM PERNAH LANGGANAN ---
                    
                    // Lempar ke halaman pemilihan paket 
                    return redirect()->route('user.plans.index') 
                        ->with('error', 'Silakan pilih dan berlangganan paket terlebih dahulu untuk mengakses fitur premium ini.');
                }
            }
        }

        return $next($request);
    }
}