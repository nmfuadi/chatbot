<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Invoice; // Tambahan untuk model Invoice
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str; // Tambahan untuk fungsi Str::random

class SubscriptionController extends Controller
{
    // Menampilkan daftar paket ke user
    public function index()
    {
        // Ambil semua paket yang aktif
        $plans = Plan::where('is_active', true)->get();
        return view('member.plans', compact('plans'));
    }

    // Memproses pilihan paket (Versi Baru dengan Invoice)
    public function subscribe(Request $request, Plan $plan)
    {
        $user = Auth::user();

        // Tentukan masa aktif (Contoh: Uji Coba 7 hari, Berbayar 30 hari)
        $daysActive = $plan->price == 0 ? 7 : 30;

        // 1. Buat record langganan di database
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => $plan->price == 0 ? 'active' : 'pending',
            'starts_at' => $plan->price == 0 ? Carbon::now() : null,
            'ends_at' => $plan->price == 0 ? Carbon::now()->addDays($daysActive) : null,
        ]);

        // 2. Jika GRATIS: Langsung aktifkan user dan ke dashboard
        if ($plan->price == 0) {
            $user->update(['subscription_status' => 'active']);
            return redirect()->route('dashboard')->with('success', 'Paket Uji Coba berhasil diaktifkan! Selamat datang di Dashboard.');
        }

        // 3. Jika BERBAYAR: Buat Invoice
        $invoice = Invoice::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
            'amount' => $plan->price,
            'status' => 'unpaid'
        ]);

        // Arahkan ke halaman Invoice yang baru dibuat
        return redirect()->route('user.invoice.show', $invoice->id);
    }
}