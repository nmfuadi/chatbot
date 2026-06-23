<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WidgetSetting;
use App\Models\ChatSession;
use App\Models\LiveChatMessage;
use App\Models\ProductKnowledge;
use App\Models\AiSetting;

class WidgetApiController extends Controller
{
    // 1. Ambil Pengaturan Widget (Warna, Logo, Status)
    public function settings($user_id)
    {
        $setting = WidgetSetting::where('user_id', $user_id)->where('is_active', true)->first();
        if (!$setting) {
            return response()->json(['error' => 'Widget tidak aktif atau tidak ditemukan'], 404);
        }
        return response()->json($setting);
    }

    // 2. Mulai Sesi Chat (Form Nama & WA)
    public function startSession(Request $request)
    {
        $request->validate(['user_id' => 'required', 'name' => 'required', 'phone' => 'required']);

        // Normalisasi Nomor
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }

        // Cari sesi yang sudah ada, atau buat baru sebagai 'widget'
        $session = ChatSession::firstOrCreate(
            ['user_id' => $request->user_id, 'customer_phone' => $phone],
            ['customer_name' => $request->name, 'is_ai_active' => true, 'source' => 'widget']
        );

        return response()->json(['session_id' => $session->id, 'customer_name' => $session->customer_name]);
    }

    // 3. Tarik Riwayat Pesan (Untuk Sinkronisasi)
    public function getMessages($sessionId)
    {
        // Mengambil riwayat pesan widget berdasarkan chat_session_id
        $messages = LiveChatMessage::where('chat_session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();
    
        return response()->json($messages);
    }

    // 4. API UNTUK DIPANGGIL PYTHON: Ambil Konteks SOP & History
    public function getContext(Request $request)
    {
        $session = ChatSession::findOrFail($request->session_id);
        $member = User::find($session->user_id);
        
        $pk = ProductKnowledge::where('user_id', $member->id)->first();
        $aiSetting = AiSetting::where('user_id', $member->id)->first(); 
        
        $knowledge = $pk->content ?? "Kamu adalah asisten CS yang ramah.";
        
        // Ambil 10 histori terakhir dari tabel LiveChatMessage
        $histories = LiveChatMessage::where('chat_session_id', $session->id)->latest()->take(10)->get()->reverse();
        $formattedHistory = [];
        foreach ($histories as $h) {
            $role = ($h->sender_type == 'customer') ? 'user' : 'assistant';
            $formattedHistory[] = ['role' => $role, 'content' => $h->message];
        }

        return response()->json([
            'knowledge' => $knowledge,
            'history' => $formattedHistory,
            'is_ai_active' => $session->is_ai_active,
            'ai_model' => $aiSetting->ai_model ?? 'google/gemma-3-4b-it',
            'deepinfra_api_key' => $aiSetting->deepinfra_api_key ?? null,
            'is_blacklisted' => false // Widget belum perlu blacklist nomor HP
        ]);
    }

    // 5. API UNTUK JS WIDGET: Simpan Pesan Customer Saja
    public function saveCustomerMessage(Request $request)
    {
        // UBAH: Validasi menangkap 'session_id' sesuai kiriman JS (bukan chat_session_id)
        $request->validate([
            'session_id' => 'required|exists:chat_sessions,id',
            'message' => 'required|string',
        ]);

        // Simpan pesan dari pengguna widget masuk ke live_chat_messages
        $message = LiveChatMessage::create([
            'chat_session_id' => $request->session_id, // UBAH: Ambil dari session_id
            'message'         => $request->message,
            'sender_type'     => 'customer', 
            'is_read'         => 0
        ]);

        return response()->json([
            'success' => true,
            'data'    => $message
        ]);
    }

    // 6. API UNTUK PYTHON: Simpan Balasan AI
    public function saveReply(Request $request)
    {
        // UBAH: Tambahkan validasi agar aman dan datanya valid
        $request->validate([
            'session_id' => 'required|exists:chat_sessions,id',
            'message' => 'required|string',
        ]);

        // UBAH: Tambahkan is_read agar strukturnya sama dengan tabel
        $aiMessage = LiveChatMessage::create([
            'chat_session_id' => $request->session_id,
            'message'         => $request->message,
            'sender_type'     => 'ai',
            'is_read'         => 0
        ]);

        return response()->json(['status' => 'success', 'data' => $aiMessage]);
    }
}