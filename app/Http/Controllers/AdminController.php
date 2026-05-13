<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
// Tambahan import untuk fitur Dashboard Admin
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    // A. List Member & Input API Wablas
    public function members()
    {
        $members = User::where('role', 'member')->get();
        return view('admin.members', compact('members'));
    }

    public function updateMemberWablas(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'wablas_api_key' => $request->wablas_api_key,
            'wablas_secret_key' => $request->wablas_secret_key,
            'wablas_device_id' => $request->wablas_device_id,
        ]);
        return back()->with('success', 'Data Wablas Member berhasil diupdate.');
    }

    // B. Approval Pembayaran
    public function approvePayment($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $payment->update(['status' => 'approved']);
        
        // Aktifkan User
        $payment->user->update(['status' => 'active']);

        return back()->with('success', 'Pembayaran disetujui, Member sekarang Aktif.');
    }

    // ==========================================================
    // C. TAMBAHAN: Dashboard Admin
    // ==========================================================
    public function dashboard()
    {
        // 1. Metrik Bisnis Ringan
        $totalMembers = User::where('role', '!=', 'admin')->count();
        $newMembersThisMonth = User::where('role', '!=', 'admin')
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();

        // 2. Metrik Traffic Bot Hari Ini (Baca file log, sangat ringan)
        $today = Carbon::today()->format('Y-m-d');
        $logPath = storage_path("app/bot-logs/bot-{$today}.log");
        
        $totalMessagesToday = 0;
        $totalErrorsToday = 0;

        if (File::exists($logPath)) {
            $lines = file($logPath);
            $totalMessagesToday = count($lines);
            // Hitung sekilas yang error hari ini
            foreach ($lines as $line) {
                if (strpos($line, '"status":"error"') !== false) {
                    $totalErrorsToday++;
                }
            }
        }

        // 3. Member Terbaru (Ambil 5 data terakhir)
        $recentMembers = User::where('role', '!=', 'admin')
                             ->orderBy('created_at', 'desc')
                             ->take(5)
                             ->get();

        return view('admin.dashboard', compact(
            'totalMembers', 'newMembersThisMonth', 'totalMessagesToday', 'totalErrorsToday', 'recentMembers'
        ));
    }
}