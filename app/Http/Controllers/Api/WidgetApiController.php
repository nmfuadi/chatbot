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
use App\Models\DynamicCatalog; // <-- Model Katalog Baru
use App\Models\Subscription; // <-- Model Langganan
use App\Http\Controllers\PaymentController; // <-- Controller Payment
use Illuminate\Support\Facades\Log;

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

    // 4. API UNTUK DIPANGGIL PYTHON: Ambil Konteks SOP & History (DISINKRONKAN DENGAN WA)
    public function getContext(Request $request)
    {
        try {
            // --- SISTEM KEAMANAN (API KEY DARI PYTHON) ---
            $apiKey = $request->header('x-tera-api-key');
            if ($apiKey !== 'TERA_SECURE_KEY_2026_XYZ') {
                return response()->json(['error' => 'Akses Ditolak! API Key Tidak Valid.'], 401);
            }

            $session = ChatSession::findOrFail($request->session_id);
            $member = User::find($session->user_id);
            $pesan = strtolower(trim($request->message ?? ''));

            // Fitur manual pause/resume via chat 
            if ($pesan === '#s') {
                $session->update(['is_ai_active' => false]);
                return response()->json([
                    'success' => true,
                    'is_ai_active' => false,
                    'message' => 'AI is paused via #s command.'
                ]);
            } elseif ($pesan === '#c') {
                $session->update(['is_ai_active' => true]);
            }

            // --- CEK STATUS AI (TERMASUK DARI AUTO-PAUSE OWNER) ---
            if ($session->is_ai_active == 0 || $session->is_ai_active == false) {
                return response()->json([
                    'success' => true, 
                    'is_ai_active' => false,
                    'message' => 'Bot tidak merespon karena chat di-takeover oleh Owner.'
                ]);
            }

            // ====================================================================
            // --- LOGIKA PENGECEKAN SUBSCRIPTION & KUOTA AI ---
            // ====================================================================
            $activeSub = Subscription::where('user_id', $member->id)
                            ->where('status', 'active')
                            ->where('ends_at', '>', now())
                            ->with('plan')
                            ->first();

            if (!$activeSub) {
                $session->update(['is_ai_active' => false]);
                return response()->json(['error' => 'Langganan tidak aktif'], 403);
            } else {
                $maxMessages = $activeSub->plan->max_messages ?? 0;
                if ($maxMessages > 0) {
                    $usageCount = $activeSub->usage_count;
                    if ($usageCount >= $maxMessages) {
                        $activeSub->update(['status' => 'expired']);
                        $member->update(['subscription_status' => 'expired']);
                        PaymentController::generateRenewalInvoice($member);
                        Log::info("WidgetApiController: Member {$member->id} kehabisan kuota. Langganan di-expired-kan.");
                        $session->update(['is_ai_active' => false]);
                        return response()->json(['error' => 'Kuota pesan habis'], 403);
                    }
                }
            }
            // ====================================================================

            // Ambil SOP dasar
            $pkRules = ProductKnowledge::where('user_id', $member->id)->first();
            $knowledge = $pkRules ? $pkRules->content : "Tidak ada SOP khusus.";

            // ====================================================================
            // --- MESIN PENCARI KATALOG PINTAR (RAG LITE DARI GOOGLE SHEETS) ---
            // ====================================================================
            $triggerWords = ['stok', 'ada', 'harga', 'jual', 'ukuran', 'warna', 'ready', 'katalog', 'produk', 'pesan', 'beli', 'spesifikasi', 'tipe', 'model'];
            $isAskingProduct = false;
            
            foreach ($triggerWords as $word) {
                if (stripos($pesan, $word) !== false) {
                    $isAskingProduct = true;
                    break;
                }
            }

            if ($isAskingProduct) {
                $words = array_filter(explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $pesan)), function($w) {
                    return strlen($w) > 3;
                });

                $query = DynamicCatalog::where('user_id', $member->id);

                if (!empty($words)) {
                    $query->where(function($q) use ($words) {
                        foreach($words as $w) {
                            $q->orWhere('raw_data', 'LIKE', '%' . $w . '%');
                        }
                    });
                }

                $results = $query->limit(5)->get();

                if ($results->isNotEmpty()) {
                    $knowledge .= "\n\n[SISTEM INFO: HASIL PENCARIAN DATABASE KATALOG SAAT INI]\n";
                    $knowledge .= "Gunakan data berikut untuk menjawab pertanyaan user (Hanya sebutkan yang ditanya atau relevan):\n";
                    foreach ($results as $item) {
                        $knowledge .= "- " . json_encode($item->raw_data, JSON_UNESCAPED_UNICODE) . "\n";
                    }
                } else {
                    $knowledge .= "\n\n[SISTEM INFO: Maaf, barang atau ukuran yang dicari tidak ditemukan di database saat ini.]\n";
                }
            }

            // ====================================================================
            // --- BATASAN KONTEKS & PERINTAH AUTO-STOP ---
            // ====================================================================
            $knowledge .= "\n=== BATASAN KONTEKS & PERINTAH AUTO-STOP ===\n";
            $knowledge .= "Kamu adalah Asisten Bisnis profesional. Tolong perhatikan riwayat obrolan sebelumnya. Jika customer membahas hal yang SAMA SEKALI TIDAK RELEVAN dengan bisnis/produk, ikuti 2 tahapan ini secara ketat:\n";
            $knowledge .= "TAHAP 1 (Peringatan & Closing Statement): \n";
            $knowledge .= "Jika customer baru pertama kali melenceng dari topik, tolak dengan sangat sopan dan berikan closing statement. tanpa memberikan info TAHAP 1 ATAU TAHAP 2  \n";
            $knowledge .= "Contoh balasan Tahap 1: 'Maaf kak, aku hanya asisten virtual untuk melayani pesanan dan info produk. Jika sudah tidak ada yang bisa aku bantu seputar produk kami, aku izin mengakhiri sesi chat ini ya kak 🙏'\n\n";
            $knowledge .= "TAHAP 2 (Eksekusi Penghentian): \n";
            $knowledge .= "Jika di chat selanjutnya customer MASIH membalas hal di luar konteks, atau sekadar membalas closing statement-mu tanpa ada niat bertanya soal produk, kamu WAJIB membalas HANYA dengan SATU KATA ini: [AUTO_STOP]\n";
            $knowledge .= "PENTING: Pada Tahap 2, JANGAN tambahkan kata maaf atau kata-kata lainnya. CUKUP KETIK: [AUTO_STOP]\n";

            // ====================================================================
            // --- PROSES DYNAMIC PROMPT BUILDER BERDASARKAN INPUT USER ---
            // ====================================================================
            $aiName           = $pkRules->ai_name ?? 'Rani';
            $customerCall     = $pkRules->customer_call ?? 'Kakak';
            $gayaBahasaOpt    = $pkRules->gaya_bahasa ?? 'santai';
            $gayaBerpikirOpt  = $pkRules->gaya_berpikir ?? 'strict_sop';
            $objectiveOpt     = $pkRules->primary_objective ?? 'soft_selling';
            $replyLengthOpt   = $pkRules->reply_length ?? 'singkat';
            $fallbackOpt      = $pkRules->fallback_behavior ?? 'arahkan_cs';
            $useEmojiOpt      = $pkRules->use_emoji ?? 'banyak_emoji';

            // Pemetaan Instruksi (Singkat untuk efisiensi kode)
            $gayaBahasaInstruction = $gayaBahasaOpt === 'formal' ? "Gunakan bahasa Indonesia baku, sopan, formal." : ($gayaBahasaOpt === 'gaul_digital' ? "Gaya anak muda metropolitan, gaul kekinian." : "Gunakan bahasa sehari-hari yang santai, hangat.");
            $gayaBerpikirInstruction = $gayaBerpikirOpt === 'flexible_knowledge' ? "Fleksibel, edukasi secara luas lalu hubungkan ke produk." : "SAKLEK (STRICT). Jawab HANYA berdasarkan SOP/Katalog.";
            $objectiveInstruction = $objectiveOpt === 'hard_selling' ? "HARD SELLING. Dorong agresif untuk beli." : ($objectiveOpt === 'customer_service' ? "CUSTOMER SERVICE. Berikan pelayanan informasi super ramah tanpa paksaan membeli." : "SOFT SELLING. Bangun kenyamanan lalu tawarkan produk.");
            $lengthInstruction = $replyLengthOpt === 'sedang' ? "Ukuran SEDANG, 1-2 paragraf pendek." : ($replyLengthOpt === 'detail' ? "DETAIL, komprehensif, gunakan bullet points." : "SUPER SINGKAT, 1-3 kalimat.");
            $fallbackInstruction = $fallbackOpt === 'jujur_pivot' ? "Katakan tidak tahu, lalu arahkan topik ke keunggulan produk." : "Jujur tidak tahu, tawarkan disambungkan ke CS Manusia.";
            $emojiInstruction = $useEmojiOpt === 'tanpa_emoji' ? "DILARANG menggunakan emoji." : "Gunakan banyak emoji relevan.";

            // Pipeline
            $customObjections = $pkRules->objection_reasons ?? "ongkir_mahal, budget_kurang, kompetitor, slow_respon";
            $rBaru    = $pkRules->lead_rule_baru ?? "Baru menyapa.";
            $rProsp   = $pkRules->lead_rule_prospect ?? "Tanya produk/harga.";
            $rHot     = $pkRules->lead_rule_hot_prospek ?? "Sangat serius ingin membeli.";
            $rDeal    = $pkRules->lead_rule_deal ?? "Setuju membeli/minta rekening.";
            $rClosing = $pkRules->lead_rule_closing ?? "Kirim bukti transfer.";
            $rGagal   = $pkRules->lead_rule_gagal ?? "Batal pesan.";

            $jsonPrompt = '
            Kamu adalah Asisten Virtual profesional bernama: ' . $aiName . '.
            Panggil lawan bicaramu (pelanggan) dengan sebutan khusus: "' . $customerCall . '".

            🚨 ATURAN PERILAKU KOMUNIKASI:
            1. GAYA BAHASA: ' . $gayaBahasaInstruction . '
            2. BATASAN BERPIKIR: ' . $gayaBerpikirInstruction . '
            3. MISI UTAMA: ' . $objectiveInstruction . '
            4. PANJANG BALASAN: ' . $lengthInstruction . '
            5. EMOJI: ' . $emojiInstruction . '
            6. JIKA TIDAK TAHU: ' . $fallbackInstruction . '
            7. Akhiri dengan satu pancingan pendek agar obrolan berlanjut.

            OUTPUT WAJIB RAW JSON DENGAN SKEMA BERIKUT:
            {
            "reply_text": "Isi balasanmu yang mematuhi aturan.",
            "lead_status": "baru" | "prospect" | "hot_prospek" | "deal" | "closing" | "gagal",
            "objection_reason": "PILIH DARI: [' . $customObjections . '] atau isi \"null\"",
            "ads_source": "Ekstrak nama promo/iklan (misal \'Organik\').",
            "chat_summary": "1 kalimat ringkasan percakapan.",
            "lead_score": Isi angka 1-100 probabilitas closing,
            "buyer_character": "PILIH: \"To The Point\" | \"Banyak Tanya\" | \"Ragu-Ragu\" | \"Skeptis\" | \"Ramah\""
            }

            ATURAN "lead_status":
            1. baru = ' . $rBaru . '
            2. prospect = ' . $rProsp . '
            3. hot_prospek = ' . $rHot . '
            4. deal = ' . $rDeal . '
            5. closing = ' . $rClosing . '
            6. gagal = ' . $rGagal . '

            CATATAN: Dilarang menggunakan markdown (```json) pada output, berikan JSON murni.';

            $knowledge .= "\n\n=== INSTRUKSI SISTEM & FORMAT JSON (WAJIB DIPATUHI) ===\n" . $jsonPrompt;

            // ====================================================================
            // --- LOGIKA HISTORY CHAT (KHUSUS WIDGET) ---
            // ====================================================================
            $histories = LiveChatMessage::where('chat_session_id', $session->id)->latest()->take(10)->get()->reverse();
            $formattedHistory = [];
            foreach ($histories as $h) {
                $role = ($h->sender_type == 'customer') ? 'user' : 'assistant';
                $formattedHistory[] = ['role' => $role, 'content' => $h->message];
            }

            // Sesuaikan pencarian menggunakan device_id milik member
$aiSetting = AiSetting::where('device_id', $member->wablas_device_id)->first(); 

            return response()->json([
                'knowledge' => $knowledge,
                'history' => $formattedHistory,
                'is_ai_active' => $session->is_ai_active,
                'ai_model' => $aiSetting->ai_model ?? 'google/gemma-3-4b-it',
                'deepinfra_api_key' => $aiSetting->deepinfra_api_key ?? null,
                'is_blacklisted' => false 
            ]);

        } catch (\Throwable $e) {
            Log::error("CRASH Widget Context: " . $e->getMessage() . " on line " . $e->getLine());
            return response()->json([
                'error' => 'TERJADI CRASH DI LARAVEL',
                'pesan_asli' => $e->getMessage(),
                'baris' => $e->getLine()
            ], 500);
        }
    }

    // 5. API UNTUK JS WIDGET: Simpan Pesan Customer Saja
    public function saveCustomerMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:chat_sessions,id',
            'message' => 'required|string',
        ]);

        $message = LiveChatMessage::create([
            'chat_session_id' => $request->session_id,
            'message'         => $request->message,
            'sender_type'     => 'customer', 
            'is_read'         => 0
        ]);

        return response()->json(['success' => true, 'data' => $message]);
    }

    // 6. API UNTUK PYTHON: Simpan Balasan AI
    public function saveReply(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:chat_sessions,id',
            'message' => 'required|string',
        ]);

        $aiMessage = LiveChatMessage::create([
            'chat_session_id' => $request->session_id,
            'message'         => $request->message,
            'sender_type'     => 'ai',
            'is_read'         => 0
        ]);

        return response()->json(['status' => 'success', 'data' => $aiMessage]);
    }
}