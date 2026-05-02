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
        $user = auth()->user();
        $plans = \App\Models\Plan::all();
        
        // CEK: Apakah user ini sudah pernah mengambil paket gratis (price = 0) sebelumnya?
        $hasUsedTrial = \App\Models\Subscription::where('user_id', $user->id)
                        ->whereHas('plan', function($query) {
                            $query->where('price', 0);
                        })->exists();
    
        return view('member.plans', compact('plans', 'hasUsedTrial'));
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

    public function store(Request $request, Plan $plan)
{
    $user = auth()->user();

    // ATURAN 3: Cek apakah user sudah punya langganan yang aktif
    if ($user->subscription_status === 'active') {
        return back()->with('error', 'Anda sudah memiliki paket langganan yang aktif. Silakan batalkan (cancel) terlebih dahulu jika ingin mengganti ke paket lain.');
    }

    // Cek apakah user punya invoice unpaid (mencegah spam klik order)
    $unpaidInvoice = \App\Models\Invoice::where('user_id', $user->id)
                        ->where('status', 'unpaid')
                        ->first();

    if ($unpaidInvoice) {
        return redirect()->route('user.invoice.show', $unpaidInvoice->id)
            ->with('error', 'Anda masih memiliki tagihan yang belum dibayar. Selesaikan atau batalkan tagihan ini terlebih dahulu.');
    }

    // ... Jika aman, lanjutkan proses buat Subscription & Invoice baru ...
}

public function cancelPlan(Request $request)
{
    $user = auth()->user();

    // 1. Reset Status Langganan di Tabel User
    $user->update(['subscription_status' => 'unpaid']);

    // 2. Batalkan Subscription yang sedang berjalan (Active)
    $activeSubscriptions = \App\Models\Subscription::where('user_id', $user->id)
                            ->where('status', 'active')
                            ->get();

    foreach ($activeSubscriptions as $sub) {
        $sub->update([
            'status' => 'cancelled',
            'ends_at' => now(), // Langsung hentikan akses
        ]);
    }

    // 3. Batalkan juga Invoice yang terkait (baik yang sudah Paid maupun Unpaid)
    $invoices = \App\Models\Invoice::where('user_id', $user->id)
                ->whereIn('status', ['unpaid', 'paid'])
                ->get();

    foreach ($invoices as $inv) {
        $inv->update(['status' => 'cancelled']);
    }

    return redirect()->route('user.plans.index')
        ->with('success', 'Paket lama telah dibatalkan. Anda sekarang bisa memilih paket baru.');
}

}