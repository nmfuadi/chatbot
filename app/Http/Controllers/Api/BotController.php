<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductKnowledge;
use App\Models\ChatHistory;
use App\Models\ChatSession; // Tambahkan model ini untuk memanggil tabel chat_sessions

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

        // Ambil History Chat 24 Jam Terakhir untuk customer ini
        $histories = ChatHistory::where('user_id', $member->id)
            ->where('customer_wa', $request->customer_phone)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'asc')
            ->get();

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