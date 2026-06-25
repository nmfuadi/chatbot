<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductKnowledge;
use App\Models\ChatHistory;
use App\Models\ChatSession;
// use App\Models\Catalog; <-- TIDAK DIPAKAI LAGI KARENA DIGANTI DYNAMIC CATALOG
use App\Models\DynamicCatalog; // <-- MODEL BARU UNTUK GOOGLE SHEETS
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription;
use App\Http\Controllers\PaymentController;
use App\Models\AiSetting;

class BotController extends Controller {
    
    // ====================================================================
    // 1. FUNGSI UNTUK MEMBERIKAN KONTEKS & DATA KE PYTHON / AI
    // ====================================================================
    public function getContext(Request $request) {
        
        // --- 1. SISTEM KEAMANAN (API KEY DARI PYTHON) ---
        $apiKey = $request->header('x-tera-api-key');
        if ($apiKey !== 'TERA_SECURE_KEY_2026_XYZ') {
            return response()->json(['error' => 'Akses Ditolak! API Key Tidak Valid.'], 401);
        }

        $request->validate([
            'device_id' => 'required', 
            'customer_phone' => 'required',
            'message' => 'nullable|string'
        ]);

        $member = User::where('wablas_device_id', $request->device_id)->first();

        if (!$member) {
            return response()->json(['error' => 'Member tidak ditemukan'], 404);
        }

        // --- NORMALISASI NOMOR TELEPON ---
        $incomingPhone = $request->customer_phone;
        $cleanPhone = str_replace('@s.whatsapp.net', '', $incomingPhone);

        // ==========================================================
        // --- CEK BLACKLIST ---
        // ==========================================================
        $isBlacklisted = \App\Models\Blacklist::where('user_id', $member->id)
                            ->where('phone_number', $cleanPhone)
                            ->exists();

        if ($isBlacklisted) {
            return response()->json([
                'success' => true,
                'is_ai_active' => false,
                'is_blacklisted' => true, // Kirim sinyal ini ke Python
                'message' => 'Nomor ini masuk dalam daftar Blacklist.'
            ]);
        }
        // ==========================================================

        // --- MENGAMBIL ATAU MEMBUAT SESI CHAT ---
        $session = ChatSession::where('user_id', $member->id)
            ->where(function($query) use ($incomingPhone, $cleanPhone) {
                $query->where('customer_phone', $incomingPhone)
                      ->orWhere('customer_phone', $cleanPhone)
                      ->orWhere('customer_phone', $cleanPhone . '@s.whatsapp.net');
            })->first();

        if (!$session) {
            $session = ChatSession::create([
                'user_id' => $member->id,
                'customer_phone' => $cleanPhone,
                'customer_name' => $request->customer_name ?? 'Customer Baru',
                'is_ai_active' => true 
            ]);
        }

        $pesan = strtolower(trim($request->message));
        
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
                'message' => 'Bot tidak merespon karena chat di-takeover oleh Owner (is_ai_active = 0).'
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
                    \Log::info("BotController: Member {$member->id} kehabisan kuota ({$maxMessages} pesan). Langganan di-expired-kan & AI dihentikan.");
                    $session->update(['is_ai_active' => false]);
                    return response()->json(['error' => 'Kuota pesan habis'], 403);
                }
            }
        }
        // ====================================================================

        // Ambil SOP dasar
        $knowledge = ProductKnowledge::where('user_id', $member->id)
                        ->pluck('content')->implode("\n\n");

        if(empty($knowledge)) $knowledge = "Tidak ada SOP khusus.";


        // ====================================================================
        // --- 2. MESIN PENCARI KATALOG PINTAR (RAG LITE DARI GOOGLE SHEETS) ---
        // ====================================================================
        $defaultTriggers = "stok, ada, harga, jual, ukuran, warna, ready, katalog, produk, pesan, beli, spesifikasi, tipe, model";
        $savedTriggers = $pkRules->catalog_trigger_words ?? $defaultTriggers;
        $triggerWords = array_filter(array_map('trim', explode(',', $savedTriggers)));
        $isAskingProduct = false;
        
        foreach ($triggerWords as $word) {
            if (stripos($pesan, $word) !== false) {
                $isAskingProduct = true;
                break;
            }
        }

        if ($isAskingProduct) {
            // Ambil kata-kata kunci dari pesan user (hilangkan simbol dan kata < 3 huruf)
            $words = array_filter(explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $pesan)), function($w) {
                return strlen($w) > 3;
            });

            $query = DynamicCatalog::where('user_id', $member->id);

            // Cari di dalam field JSON 'raw_data'
            if (!empty($words)) {
                $query->where(function($q) use ($words) {
                    foreach($words as $w) {
                        $q->orWhere('raw_data', 'LIKE', '%' . $w . '%');
                    }
                });
            }

            // AMBIL MAKSIMAL 5 DATA SAJA AGAR HEMAT TOKEN
            $results = $query->limit(5)->get();

            if ($results->isNotEmpty()) {
                $knowledge .= "\n\n[SISTEM INFO: HASIL PENCARIAN DATABASE KATALOG SAAT INI]\n";
                $knowledge .= "Gunakan data berikut untuk menjawab pertanyaan user (Hanya sebutkan yang ditanya atau relevan):\n";
                foreach ($results as $item) {
                    // Merubah format Array/JSON menjadi teks yang mudah dibaca AI
                    $knowledge .= "- " . json_encode($item->raw_data, JSON_UNESCAPED_UNICODE) . "\n";
                }
            } else {
                $knowledge .= "\n\n[SISTEM INFO: Maaf, barang atau ukuran yang dicari tidak ditemukan di database saat ini.]\n";
            }
        }
        // ====================================================================

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
        $pkRules = ProductKnowledge::where('user_id', $member->id)->first();

        // 1. Siapkan Fallback Value
        $aiName           = $pkRules->ai_name ?? 'Rani';
        $customerCall     = $pkRules->customer_call ?? 'Kakak';
        $gayaBahasaOpt    = $pkRules->gaya_bahasa ?? 'santai';
        $gayaBerpikirOpt  = $pkRules->gaya_berpikir ?? 'strict_sop';
        $objectiveOpt     = $pkRules->primary_objective ?? 'soft_selling';
        $replyLengthOpt   = $pkRules->reply_length ?? 'singkat';
        $fallbackOpt      = $pkRules->fallback_behavior ?? 'arahkan_cs';
        $useEmojiOpt      = $pkRules->use_emoji ?? 'banyak_emoji';

        // 2. Pemetaan Gaya Bahasa
        $gayaBahasaInstruction = "Gunakan bahasa Indonesia sehari-hari yang santai, hangat, akrab, dan gunakan beberapa istilah gaul digital (seperti: ngonten, viral, ngehook).";
        if ($gayaBahasaOpt === 'formal') {
            $gayaBahasaInstruction = "Gunakan bahasa Indonesia yang baku, sopan, formal, profesional, dan ikuti kaidah EYD dengan baik.";
        } elseif ($gayaBahasaOpt === 'gaul_digital') {
            $gayaBahasaInstruction = "Gunakan gaya bahasa anak muda metropolitan Jakarta, boleh gunakan singkatan umum dan istilah-istilah gaul terkini.";
        }

        // 3. Pemetaan Gaya Berpikir (Saklek vs Luas)
        $gayaBerpikirInstruction = "Kamu WAJIB berpikir SAKLEK (STRICT). Kamu hanya boleh menjawab berdasarkan data yang ada di Data SOP/Katalog. Jika pelanggan bertanya hal di luar data tersebut, kamu DILARANG mengarang bebas.";
        if ($gayaBerpikirOpt === 'flexible_knowledge') {
            $gayaBerpikirInstruction = "Kamu diberikan kebebasan berpikir yang LUAS. Jika pelanggan bertanya tentang teori, konsep, tips, atau strategi umum, gunakan pengetahuan globalmu sebagai AI untuk mengedukasi mereka secara cerdas, lalu hubungkan secara halus ke produk kita.";
        }

        // 4. Pemetaan Tujuan Utama (Objective)
        // 4. Pemetaan Tujuan Utama (Objective)
        $objectiveInstruction = "Tujuan utamamu adalah melakukan SOFT SELLING. Bangun kenyamanan, edukasi pelanggan terlebih dahulu, baru tawarkan produk kita secara halus di akhir obrolan.";
        if ($objectiveOpt === 'hard_selling') {
            $objectiveInstruction = "Tujuan utamamu adalah HARD SELLING. Dorong pelanggan secara agresif tapi tetap sopan untuk langsung melakukan pembelian, check out, atau transfer pembayaran sekarang juga.";
        } elseif ($objectiveOpt === 'customer_service') {
            // SUNTIKAN PROMPT SUPER CS DARI PARA PAKAR
            $objectiveInstruction = "Tujuan utamamu adalah CUSTOMER SERVICE dengan standar [SUPER CS MODE] (Five-Star Service Excellence). Fokus total pada pelayanan informasi yang super ramah, sabar, sangat empatik, dan solutif TANPA ada paksaan untuk membeli produk. Kamu wajib mematuhi 'Golden Rules' dari para pakar Customer Experience (CX) dunia berikut secara ketat:\n" .
                                    "- EMPATI & VALIDASI: Berikan validasi hangat di awal kalimat terhadap pertanyaan atau kendala pelanggan (Contoh: 'Pertanyaan yang bagus sekali Kak, dengan senang hati Rani jelaskan...').\n" .
                                    "- POSITIVE PHRASING: Gunakan bahasa yang selalu optimis dan solutif. DILARANG menggunakan kata mati seperti 'Tidak bisa', 'Tidak ada', atau 'Bukan tugas saya'. Ganti dengan alternatif solusi yang tersedia (Contoh: 'Saat ini warna tersebut sedang dikemas ulang, sebagai gantinya Rani punya rekomendasi warna yang tak kalah cantik untuk Kakak...').\n" .
                                    "- CLEAR & STRUCTURAL RESPONDING: Berikan penjelasan yang runut, bersih, step-by-step, dan sangat mudah dipahami oleh orang awam sekalipun.\n" .
                                    "- ACTIVE LISTENING MODE: Fokus menjawab tepat pada inti point yang ditanyakan pelanggan, dilarang memberikan informasi yang berputar-putar (info dumping).\n" .
                                    "- GO THE EXTRA MILE: Di setiap akhir respon, selalu tawarkan bantuan tambahan dengan tulus untuk menunjukkan dedikasi pelayanan terbaik.";
        }

        // 5. Pemetaan Panjang Balasan
        $lengthInstruction = "Jawabanmu HARUS SUPER SINGKAT, maksimal hanya 1 hingga 3 kalimat pendek per satu kali balas. Jangan pernah melakukan info dumping!";
        if ($replyLengthOpt === 'sedang') {
            $lengthInstruction = "Jawabanmu berukuran SEDANG, sekitar 1 hingga 2 paragraf pendek agar penjelasan produk tersampaikan dengan jelas.";
        } elseif ($replyLengthOpt === 'detail') {
            $lengthInstruction = "Jawabanmu HARUS DETAIL, komprehensif, dan gunakan poin-poin (bullet points) jika menjelaskan spesifikasi produk.";
        }

        // 6. Pemetaan Sikap Jika AI Tidak Tahu
        $fallbackInstruction = "Katakan secara jujur dan tawarkan dengan sopan untuk menyambungkan chat ini ke Admin/CS Manusia langsung.";
        if ($fallbackOpt === 'jujur_pivot') {
            $fallbackInstruction = "Katakan kamu tidak tahu hal tersebut, lalu arahkan kembali topik obrolan secara halus ke keunggulan produk kita yang ada di SOP.";
        }

        // 7. Pemetaan Emoji
        $emojiInstruction = "Gunakan banyak emoji yang relevan di setiap baris kalimat jawabanmu agar terkesan interaktif dan ceria.";
        if ($useEmojiOpt === 'tanpa_emoji') {
            $emojiInstruction = "DILARANG KERAS menggunakan emoji apa pun. Pastikan teks jawabanmu bersih dan terlihat formal.";
        }

        // 8. Aturan Klasifikasi Pipeline (Tetap)
        $customObjections = $pkRules->objection_reasons ?? "ongkir_mahal, budget_kurang, kompetitor, slow_respon";
        $rBaru    = $pkRules->lead_rule_baru ?? "Baru masuk ke sistem WhatsApp, chat pertama kali, atau menyapa.";
        $rProsp   = $pkRules->lead_rule_prospect ?? "Pelanggan mulai aktif mengobrol, tanya produk/katalog/harga.";
        $rHot     = $pkRules->lead_rule_hot_prospek ?? "Pelanggan cocok dengan budget/kebutuhan dan sangat serius ingin membeli.";
        $rDeal    = $pkRules->lead_rule_deal ?? "Pelanggan setuju membeli, meminta nomor rekening, atau membayar booking fee.";
        $rClosing = $pkRules->lead_rule_closing ?? "Pelanggan mengirimkan bukti transfer lunas dan pembayaran dikonfirmasi.";
        $rGagal   = $pkRules->lead_rule_gagal ?? "Pelanggan membatalkan pesanan secara tegas atau menolak membeli.";

        // 9. RAKIT SUPER PROMPT DINAMISNYA
        $jsonPrompt = '
        Kamu adalah Asisten Virtual profesional bernama: ' . $aiName . '.
        Panggil lawan bicaramu (pelanggan) dengan sebutan khusus: "' . $customerCall . '".

        🚨 ATURAN PERILAKU KOMUNIKASI (WAJIB DIPATUHI):
        1. GAYA BAHASA: ' . $gayaBahasaInstruction . '
        2. BATASAN BERPIKIR: ' . $gayaBerpikirInstruction . '
        3. MISI UTAMA: ' . $objectiveInstruction . '
        4. PANJANG BALASAN: ' . $lengthInstruction . '
        5. PENGGUNAAN EMOJI: ' . $emojiInstruction . '
        6. JIKA KAMU TIDAK TAHU DATA YANG DITANYAKAN: ' . $fallbackInstruction . '
        7. Akhiri SETIAP jawabanmu dengan satu pertanyaan pancingan pendek (call to action santai) agar obrolan terus berlanjut.
        8. Gunakan format cetak tebal (*) pada nama fitur/produk penting.

        OUTPUT KAMU WAJIB BERUPA RAW JSON DENGAN SKEMA BERIKUT:
        {
        "reply_text": "Isi balasanmu yang mematuhi seluruh aturan di atas.",
        "lead_status": "baru" | "prospect" | "hot_prospek" | "deal" | "closing" | "gagal",
        "objection_reason": "PILIH SALAH SATU DARI: [' . $customObjections . '] atau isi \"null\" jika transaksi lancar",
        "ads_source": "Ekstrak nama promo/iklan dari chat pertama pelanggan, misal \'Promo IG\' atau \'Organik\'.",
        "chat_summary": "Buat 1 kalimat singkat (maks 10 kata) yang menyimpulkan inti percakapan prospek ini.",
        "lead_score": Isi dengan angka 1 sampai 100 yang menilai probabilitas closing,
        "buyer_character": "PILIH KATEGORI KEPRIBADIAN: \"To The Point\" | \"Banyak Tanya\" | \"Ragu-Ragu\" | \"Skeptis\" | \"Ramah\""
        }

        ATURAN KLASIFIKASI "lead_status":
        1. baru = ' . $rBaru . '
        2. prospect = ' . $rProsp . '
        3. hot_prospek = ' . $rHot . '
        4. deal = ' . $rDeal . '
        5. closing = ' . $rClosing . '
        6. gagal = ' . $rGagal . '

        CATATAN: Dilarang menggunakan markdown (```json) pada output, berikan JSON murni.';

        $knowledge .= "\n\n=== INSTRUKSI SISTEM & FORMAT JSON (WAJIB DIPATUHI) ===\n" . $jsonPrompt;
        // ====================================================================

        // --- LOGIKA HISTORY CHAT ---
        $histories = ChatHistory::where('user_id', $member->id)
            ->where(function($query) use ($incomingPhone, $cleanPhone) {
                $query->where('customer_wa', $incomingPhone)
                      ->orWhere('customer_wa', $cleanPhone)
                      ->orWhere('customer_wa', $cleanPhone . '@s.whatsapp.net');
            })
            ->where('created_at', '>=', now()->subDay())
            ->latest()->take(5)->get()->reverse();

        $formattedHistory = [];
        foreach ($histories as $h) {
            $formattedHistory[] = ['role' => 'user', 'content' => $h->user_message];
            if ($h->ai_response) $formattedHistory[] = ['role' => 'assistant', 'content' => $h->ai_response];
        }

        $aiSetting = AiSetting::where('device_id', $request->device_id)->first();

        // RETURN JSON KE N8N / PYTHON
        return response()->json([
            'knowledge' => $knowledge,
            'wablas_api_key' => $member->wablas_api_key,
            'wablas_secret_key' => $member->wablas_secret_key,
            'history' => $formattedHistory,
            'is_ai_active' => $session->is_ai_active,
            'is_command' => in_array($pesan, ['#s', '#c']),
            'ai_provider' => $aiSetting->ai_provider ?? 'gemini', 
            'ai_model' => $aiSetting->ai_model ?? 'gemini-flash-latest', 
            'deepinfra_api_key' => $aiSetting->deepinfra_api_key ?? null,
            'project_images' => [] 
        ]);
    }

    // ====================================================================
    // 2. FUNGSI UNTUK MENYIMPAN HISTORY CHAT 
    // ====================================================================
    public function saveHistory(Request $request) {
        
        // --- SISTEM KEAMANAN (API KEY DARI PYTHON) ---
        $apiKey = $request->header('x-tera-api-key');
        if ($apiKey !== 'TERA_SECURE_KEY_2026_XYZ') {
            return response()->json(['error' => 'Akses Ditolak! API Key Tidak Valid.'], 401);
        }

        $member = User::where('wablas_device_id', $request->device_id)->first();
        
        if ($member) {
            $aiResponse = $request->ai_response ?? '';
            $incomingPhone = $request->customer_phone;
            $cleanPhone = str_replace('@s.whatsapp.net', '', $incomingPhone);

            // --- DETEKSI SANDI RAHASIA DARI AI ---
            if (str_contains($aiResponse, '[AUTO_STOP]')) {
                \App\Models\ChatSession::where('user_id', $member->id)
                    ->where(function($query) use ($incomingPhone, $cleanPhone) {
                        $query->where('customer_phone', $incomingPhone)
                              ->orWhere('customer_phone', $cleanPhone)
                              ->orWhere('customer_phone', $cleanPhone . '@s.whatsapp.net');
                    })
                    ->update(['is_ai_active' => false]);
                
                $aiResponse = str_replace('[AUTO_STOP]', '', $aiResponse);
                \Log::info("KILL SWITCH AKTIF: Percakapan keluar konteks.");
            }

            // --- SIMPAN KE DATABASE SEPERTI BIASA ---
            ChatHistory::create([
                'user_id' => $member->id,
                'customer_wa' => $cleanPhone, 
                'user_message' => $request->user_message ?? $request->message ?? '',
                'ai_response' => trim($aiResponse),
            ]);

            // ==========================================================
            // --- TAMBAH +1 KE USAGE COUNT TANPA SYARAT ---
            // ==========================================================
            $activeSub = \App\Models\Subscription::where('user_id', $member->id)
                            ->where('status', 'active')
                            ->first();
                            
            if ($activeSub) {
                $activeSub->increment('usage_count');
                \Log::info("✅ USAGE BERTAMBAH: User ID {$member->id} sekarang menggunakan {$activeSub->usage_count} pesan.");
            } else {
                \Log::warning("⚠️ GAGAL MENAMBAH USAGE: User ID {$member->id} tidak memiliki langganan 'active'.");
            }
        }
        
        return response()->json(['status' => 'success']);
    }
}