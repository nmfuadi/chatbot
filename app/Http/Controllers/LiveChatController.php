<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\LiveChatMessage;
use Illuminate\Support\Facades\Auth;

class LiveChatController extends Controller
{
    // 1. Tampilkan Halaman Dashboard Live Chat
    public function index()
    {
        return view('livechat.index');
    }

    // 2. Ambil Daftar Sesi Chat (AJAX)
    public function getSessions()
    {
        $userId = Auth::id();
        
        // Ambil sesi chat dan hitung pesan yang belum dibaca (is_read = 0)
        $sessions = ChatSession::where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($session) {
                // Tambahkan properti unread_count ke setiap sesi
                $session->unread_count = LiveChatMessage::where('chat_session_id', $session->id)
                    ->where('sender_type', 'customer')
                    ->where('is_read', 0)
                    ->count();
                return $session;
            });

        return response()->json($sessions);
    }

   // 3. Ambil Riwayat Pesan dari Sesi Tertentu (AJAX)
   public function getMessages($sessionId)
   {
       $userId = Auth::id();
       $session = ChatSession::where('id', $sessionId)->where('user_id', $userId)->firstOrFail();

       // TANDAI SUDAH DIBACA: Karena admin sedang membuka chat ini, ubah is_read jadi 1
       LiveChatMessage::where('chat_session_id', $sessionId)
           ->where('sender_type', 'customer')
           ->where('is_read', 0)
           ->update(['is_read' => 1]);

       $messages = LiveChatMessage::where('chat_session_id', $sessionId)
           ->orderBy('created_at', 'asc')
           ->get();

       return response()->json([
           'session' => $session,
           'messages' => $messages
       ]);
   }

    // 4. Admin Kirim Pesan Manual (AJAX)
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:chat_sessions,id',
            'message' => 'required|string'
        ]);

        $userId = Auth::id();
        $session = ChatSession::where('id', $request->session_id)->where('user_id', $userId)->firstOrFail();

        // Simpan pesan sebagai Admin
        $message = LiveChatMessage::create([
            'chat_session_id' => $session->id,
            'message'         => $request->message,
            'sender_type'     => 'admin', // Tandai bahwa ini balasan Admin Manusia
            'is_read'         => 1
        ]);

        // Opsional: Update updated_at di session agar naik ke paling atas
        $session->touch();

        return response()->json(['success' => true, 'data' => $message]);
    }

    // 5. Fitur Takeover (Matikan/Nyalakan AI)
    public function toggleAi(Request $request, $sessionId)
    {
        $userId = Auth::id();
        $session = ChatSession::where('id', $sessionId)->where('user_id', $userId)->firstOrFail();
        
        // Toggle status AI
        $session->is_ai_active = !$session->is_ai_active;
        $session->save();

        return response()->json([
            'success' => true, 
            'is_ai_active' => $session->is_ai_active,
            'message' => $session->is_ai_active ? 'AI diaktifkan kembali.' : 'AI dimatikan. Sesi diambil alih oleh Admin.'
        ]);
    }

    // 6. Fitur Akhiri Sesi
    public function endSession($sessionId)
    {
        $userId = Auth::id();
        $session = ChatSession::where('id', $sessionId)->where('user_id', $userId)->firstOrFail();
        
        // Matikan AI dan bisa tambahkan penanda bahwa chat selesai
        $session->is_ai_active = false;
        $session->save();

        // Kirim pesan sistem otomatis
        LiveChatMessage::create([
            'chat_session_id' => $session->id,
            'message'         => 'Sesi obrolan telah diakhiri oleh Admin.',
            'sender_type'     => 'system',
            'is_read'         => 1
        ]);

        return response()->json(['success' => true, 'message' => 'Sesi berhasil diakhiri.']);
    }
}