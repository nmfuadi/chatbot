<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login TAPI status langganannya BUKAN active
        if (auth()->check() && auth()->user()->subscription_status !== 'active') {
            
            // Lempar ke halaman pemilihan paket
            return redirect()->route('packages.index') // Ganti dengan nama route halaman paketmu
                ->with('error', 'Silakan berlangganan paket terlebih dahulu untuk mengakses fitur premium ini.');
        }

        return $next($request);
    }
}