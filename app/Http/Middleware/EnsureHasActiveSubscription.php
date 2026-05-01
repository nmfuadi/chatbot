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
            
            // Lempar ke halaman pemilihan paket yang benar (user.plans.index)
            return redirect()->route('user.plans.index') 
                ->with('error', 'Silakan berlangganan paket terlebih dahulu untuk mengakses fitur premium ini.');
        }

        return $next($request);
    }
}