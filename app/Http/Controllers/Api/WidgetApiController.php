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
use Illuminate\Support\Facades\Http;

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
    public function getMessages($session_id)
    {
        $messages = LiveChatMessage::where('chat_session_id', $session_id)->orderBy('created_at', 'asc')->get();
        return response()->json($messages);
    }

    // 4. Kirim Pesan & Eksekusi Otak AI (DeepInfra)
    public function sendMessage(Request $request)
    {
        $session = ChatSession::findOrFail($request->session_id);

        // A. Simpan Pesan Customer
        LiveChatMessage::create([
            'chat_session_id' => $session->id,
            'message' => $request->message,
            'sender_type' => 'customer'
        ]);

        // B. Jika AI Sedang Dimatikan (Diambil alih Admin), langsung return
        if (!$session->is_ai_active) {
            return response()->json(['status' => 'success', 'ai_replied' => false]);
        }

        // C. PROSES AI (MENDUPLIKASI LOGIKA DARI PYTHON)
        $member = User::find($session->user_id);
        $pk = ProductKnowledge::where('user_id', $member->id)->first();
        $aiSetting = AiSetting::where('user_id', $member->id)->first(); // Asumsi relasi user_id ada

        $knowledge = $pk->content ?? "Kamu adalah asisten CS yang ramah.";
        $systemInstruction = $knowledge . "\n\nWAJIB balas dalam format JSON murni: {\"reply_text\": \"balasanmu\", \"lead_status\": \"prospect\"}";

        // Rakit History untuk Prompt OpenAI
        $openai_messages = [['role' => 'system', 'content' => $systemInstruction]];
        $histories = LiveChatMessage::where('chat_session_id', $session->id)->latest()->take(10)->get()->reverse();
        
        foreach ($histories as $h) {
            $role = ($h->sender_type == 'customer') ? 'user' : 'assistant';
            $openai_messages[] = ['role' => $role, 'content' => $h->message];
        }

        // Tembak ke DeepInfra
        $di_api_key = $aiSetting->deepinfra_api_key ?? "bL5jGLz5bSmO0tyGtNdGYoA6u2jMYMTW"; // Gunakan Master Key jika user tidak punya
        $di_model = $aiSetting->ai_model ?? 'google/gemma-3-4b-it';

        try {
            $response = Http::timeout(45)->withToken($di_api_key)->post('https://api.deepinfra.com/v1/openai/chat/completions', [
                'model' => $di_model,
                'max_tokens' => 2000,
                'messages' => $openai_messages,
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $rawText = $data['choices'][0]['message']['content'];
                
                // Ekstrak reply_text dari JSON
                $parsedAi = json_decode($rawText, true);
                $finalReply = $parsedAi['reply_text'] ?? "Maaf, aku sedang memproses permintaanmu.";

                // Simpan Balasan AI
                LiveChatMessage::create([
                    'chat_session_id' => $session->id,
                    'message' => $finalReply,
                    'sender_type' => 'ai'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Widget AI Error: " . $e->getMessage());
        }

        return response()->json(['status' => 'success']);
    }
}