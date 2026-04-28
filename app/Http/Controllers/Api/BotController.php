<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductKnowledge;
use App\Models\ChatHistory;
use App\Models\ChatSession; // Tambahkan model ini untuk memanggil tabel chat_sessions
use App\Models\Catalog;

class BotController extends Controller {
    
    // 1. API untuk Get SOP Context & History
    public function getContext(Request $request) {
        $request->validate([
            'device_id' => 'required',
            'customer_phone' => 'required',
            'message' => 'nullable|string' // Menerima isi pesan dari n8n
        ]);

        // Cari member berdasarkan device_id dari Wablas
        $member = User::where('wablas_device_id', $request->device_id)->first();

        if (!$member) {
            return response()->json(['error' => 'Member tidak ditemukan'], 404);
        }

        // --- AWAL LOGIKA HOLD/CONTINUE AI ---
        // Cari sesi chat yang ada, atau buat baru jika belum ada
        $session = ChatSession::firstOrCreate(
            ['user_id' => $member->id, 'customer_phone' => $request->customer_phone],
            ['customer_name' => $request->customer_name ?? 'Customer Baru' ],
            ['is_ai_active' => true] // Nilai default jika baru dibuat pertama kali
        );

        $pesan = strtolower(trim($request->message));

        // Cek apakah pesan berisi kode rahasia admin
        if ($pesan === '#s') {
            $session->update(['is_ai_active' => false]);
        } elseif ($pesan === '#c') {
            $session->update(['is_ai_active' => true]);
        }
        // --- AKHIR LOGIKA HOLD/CONTINUE AI ---


        // Ambil SOP (Product Knowledge)
        $knowledge = ProductKnowledge::where('user_id', $member->id)
                        ->pluck('content')->implode("\n\n");

        if(empty($knowledge)) $knowledge = "Tidak ada SOP khusus.";

        // --- AWAL INJEKSI DATA KATALOG ---
        $catalogs = Catalog::where('user_id', $member->id)
                        ->where('is_active', true)
                        ->get();

        if ($catalogs->isNotEmpty()) {
            $knowledge .= "\n\n=== DATA KATALOG / KETERSEDIAAN SAAT INI ===\n";
            $knowledge .= "Berikut adalah data real-time harga dan stok. Gunakan data ini untuk menjawab pertanyaan ketersediaan:\n";
            
            foreach ($catalogs as $item) {
                // Format harga ke Rupiah
                $hargaRupiah = "Rp " . number_format($item->price, 0, ',', '.');
                $knowledge .= "- Nama: {$item->item_name} | Harga: {$hargaRupiah} | Ketersediaan/Stok: {$item->stock}\n";
                
                if ($item->description) {
                    $knowledge .= "  Deskripsi/Detail: {$item->description}\n";
                }
            }
        }
        // --- AKHIR INJEKSI DATA KATALOG ---

        // Ambil History Chat 24 Jam Terakhir untuk customer ini
       // Ambil History Chat 24 Jam Terakhir, DIBATASI 5 CHAT TERAKHIR
       $histories = ChatHistory::where('user_id', $member->id)
       ->where('customer_wa', $request->customer_phone)
       ->where('created_at', '>=', now()->subDay())
       ->latest() // Ambil dari yang paling baru (descending)
       ->take(5)  // Batasi maksimal 5 baris data
       ->get()
       ->reverse(); // Balik urutannya agar kronologis dari atas ke bawah

        // Format history menjadi rapi untuk Groq AI
        $formattedHistory = [];
        foreach ($histories as $h) {
            $formattedHistory[] = ['role' => 'user', 'content' => $h->user_message];
            if ($h->ai_response) {
                $formattedHistory[] = ['role' => 'assistant', 'content' => $h->ai_response];
            }
        }

        return response()->json([
            'knowledge' => $knowledge,
            'wablas_api_key' => $member->wablas_api_key,
            'wablas_secret_key' => $member->wablas_secret_key,
            'history' => $formattedHistory,
            'is_ai_active' => $session->is_ai_active, // Kirim status aktif/tidak ke n8n
            'is_command' => in_array($pesan, ['#s', '#c']) // Tandai jika ini sekadar pesan perintah
        ]);
    }

    // 2. API untuk Save History (Dipanggil setelah AI membalas)
    public function saveHistory(Request $request) {
        $member = User::where('wablas_device_id', $request->device_id)->first();

        if ($member) {
            ChatHistory::create([
                'user_id' => $member->id,
                'customer_wa' => $request->customer_phone,
                'user_message' => $request->user_message,
                'ai_response' => $request->ai_response,
            ]);
        }
        return response()->json(['status' => 'success']);
    }
}