<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller {
    public function index() {
        $customers = ChatSession::where('user_id', Auth::id())->latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function toggleAI($id) {
        $session = ChatSession::where('user_id', Auth::id())->findOrFail($id);
        $session->update(['is_ai_active' => !$session->is_ai_active]);
        return back()->with('success', 'Status AI berhasil diubah');
    }


    
    public function history($phone) {
        // Ambil data history, urutkan dari yang paling lama ke terbaru (asc) agar enak dibaca seperti chat biasa
        $histories = ChatHistory::where('user_id', Auth::id())
                    ->where('customer_wa', $phone)
                    ->orderBy('created_at', 'asc')
                    ->get();

        // Cari nama customer dari tabel session
        $session = ChatSession::where('user_id', Auth::id())
                    ->where('customer_phone', $phone)
                    ->first();
        $customerName = $session ? $session->customer_name : 'Customer';

        return view('customers.history', compact('histories', 'phone', 'customerName'));
    }

    // Fungsi baru untuk menghapus history
    public function clearHistory($phone) {
        ChatHistory::where('user_id', Auth::id())
                    ->where('customer_wa', $phone)
                    ->delete();
                    
        return back()->with('success', 'Semua riwayat percakapan berhasil dihapus.');
    }
}