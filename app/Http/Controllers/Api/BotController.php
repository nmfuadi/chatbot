<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductKnowledge;
use App\Models\ChatHistory;
use App\Models\ChatSession;
use App\Models\Catalog;
use Illuminate\Support\Facades\Storage;
// --- TAMBAHAN BARU: Import Model Subscription & PaymentController ---
use App\Models\Subscription;
use App\Http\Controllers\PaymentController;
// ------------------------------------------------------------------

class BotController extends Controller {
    
    // ====================================================================
    // 1. FUNGSI UNTUK MEMBERIKAN KONTEKS & DATA KE N8N / AI
    // ====================================================================
    public function getContext(Request $request) {
        $request->validate([
            // 'device_id' sepertinya adalah 'instance' dari Wablas
            'device_id' => 'required', 
            'customer_phone' => 'required',
            'message' => 'nullable|string'
        ]);

        $member = User::where('wablas_device_id', $request->device_id)->first();

        if (!$member) {
            return response()->json(['error' => 'Member tidak ditemukan'], 404);
        }

        // --- NORMALISASI NOMOR TELEPON ---
        // Karena di database bisa jadi tersimpan dengan atau tanpa @s.whatsapp.net
        $incomingPhone = $request->customer_phone;
        $cleanPhone = str_replace('@s.whatsapp.net', '', $incomingPhone);

        // --- MENGAMBIL ATAU MEMBUAT SESI CHAT ---
        // Kita cari dulu menggunakan beberapa format, jika tidak ada baru kita create
        $session = ChatSession::where('user_id', $member->id)
            ->where(function($query) use ($incomingPhone, $cleanPhone) {
                $query->where('customer_phone', $incomingPhone)
                      ->orWhere('customer_phone', $cleanPhone)
                      ->orWhere('customer_phone', $cleanPhone . '@s.whatsapp.net');
            })->first();

        if (!$session) {
            $session = ChatSession::create([
                'user_id' => $member->id,
                'customer_phone' => $cleanPhone, // Simpan versi bersihnya
                'customer_name' => $request->customer_name ?? 'Customer Baru',
                'is_ai_active' => true // Default aktif untuk sesi baru
            ]);
        }

        $pesan = strtolower(trim($request->message));
        
        // Fitur manual pause/resume via chat (Mungkin ini sisa fitur lama, tapi kita pertahankan)
        if ($pesan === '#s') {
            $session->update(['is_ai_active' => false]);
            // Jika pause secara manual, hentikan eksekusi bot di sini
             return response()->json([
                'success' => true,
                'is_ai_active' => false,
                'message' => 'AI is paused via #s command.'
            ]);
        } elseif ($pesan === '#c') {
            $session->update(['is_ai_active' => true]);
        }

        // --- CEK STATUS AI (TERMASUK DARI AUTO-PAUSE OWNER) ---
        // Ini SANGAT PENTING untuk mencegah bot membalas saat owner sedang take over
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
            // Jika tidak punya paket aktif, paksa AI mati
            $session->update(['is_ai_active' => false]);
            return response()->json(['error' => 'Langganan tidak aktif'], 403);
        } else {
            // Ambil limit dari paket (misal 0 atau -1 berarti unlimited)
            $maxMessages = $activeSub->plan->max_messages ?? 0;
            
            if ($maxMessages > 0) {
                $usageCount = $activeSub->usage_count;

                if ($usageCount >= $maxMessages) {
                    // Kuota habis! Ubah status langganan menjadi expired
                    $activeSub->update(['status' => 'expired']);
                    $member->update(['subscription_status' => 'expired']);
                    
                    // Terbitkan invoice baru secara otomatis
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

        // --- INJEKSI DATA KATALOG ---
        $catalogs = Catalog::with('images')
                        ->where('user_id', $member->id)
                        ->where('is_active', true)
                        ->get();

        if ($catalogs->isNotEmpty()) {
            $knowledge .= "\n\n=== DATA KATALOG / KETERSEDIAAN SAAT INI ===\n";
            $knowledge .= "Berikut adalah data real-time harga, stok, dan link foto produk:\n";
            
            foreach ($catalogs as $item) {
                $hargaRupiah = "Rp " . number_format($item->price, 0, ',', '.');
                $knowledge .= "- Nama: {$item->item_name} | Harga: {$hargaRupiah} | Stok: {$item->stock}\n";
                if ($item->description) $knowledge .= "  Deskripsi: {$item->description}\n";

                if ($item->images->count() > 0) {
                    $allImages = $item->images->map(function($img) {
                        return asset('storage/' . $img->image_path);
                    })->implode(' | ');
                    
                    $knowledge .= "  KODE GAMBAR: [GAMBAR: {$allImages}]\n";
                }
            }

            // Aturan gambar tetap dipertahankan
            $knowledge .= "\n=== ATURAN SISTEM PENGIRIMAN GAMBAR (PENTING) ===\n";
            $knowledge .= "1. Jika customer meminta foto/gambar, kamu WAJIB menyertakan KODE GAMBAR dari data di atas di paling awal kalimat balasanmu (contoh: [GAMBAR: url1 | url2]).\n";
            $knowledge .= "2. JANGAN PERNAH meringkas atau mengubah link di dalam KODE GAMBAR. Copy-paste persis apa yang ada di data.\n";
            $knowledge .= "3. Jika customer TIDAK meminta foto, DILARANG KERAS menyertakan KODE GAMBAR.\n";
            $knowledge .= "4. Jika customer meminta foto lainnya (minta foto lagi), JANGAN KIRIM KODE GAMBAR LAGI! Katakan saja: 'Aku sudah mengirim semua foto secara detail kak'.\n";
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
        // --- PEMBARUAN PROMPT: PERSONA "TEMAN DIGITAL" & SOFT SELLING ---
        // ====================================================================
        // Mengubah jsonPrompt agar menjadi satu kesatuan instruksi yang lebih komprehensif
        
        $pkRules = ProductKnowledge::where('user_id', $member->id)->first();
        $customObjections = $pkRules->objection_reasons ?? "ongkir_mahal, budget_kurang, kompetitor, slow_respon";
        $rBaru    = $pkRules->lead_rule_baru ?? "Baru masuk ke sistem WhatsApp, chat pertama kali, atau menyapa.";
        $rProsp   = $pkRules->lead_rule_prospect ?? "Pelanggan mulai aktif mengobrol, tanya produk/katalog/harga.";
        $rHot     = $pkRules->lead_rule_hot_prospek ?? "Pelanggan cocok dengan budget/kebutuhan dan sangat serius ingin membeli.";
        $rDeal    = $pkRules->lead_rule_deal ?? "Pelanggan setuju membeli, meminta nomor rekening, atau membayar booking fee.";
        $rClosing = $pkRules->lead_rule_closing ?? "Pelanggan mengirimkan bukti transfer lunas dan pembayaran dikonfirmasi.";
        $rGagal   = $pkRules->lead_rule_gagal ?? "Pelanggan membatalkan pesanan secara tegas atau menolak membeli.";

        $jsonPrompt = '
Kamu adalah Asisten Sales / Teman Konsultasi Digital untuk bisnis ini. Tugasmu adalah melayani chat calon pembeli di WhatsApp secara natural, lalu mengarahkan mereka secara halus (soft selling) ke produk yang ada di Data SOP/Katalog.

🚨 ATURAN EMAS WHATSAPP (WAJIB DIPATUHI):
1. JANGAN PERNAH MENGETIK TEKS PANJANG LEBAR! Maksimal jawabanmu adalah 2 sampai 3 kalimat saja. Dilarang info dumping (menumpahkan semua spek produk).
2. Gaya bicara harus santai, hangat, ramah, dan natural. Gunakan panggilan "Kak" atau "Kakak". Jangan kaku seperti robot CS.
3. Jawab pertanyaan luas (konsultasi/teori) dengan cerdas, lalu HUBUNGKAN (bridging) jawabanmu dengan fitur/produk kita secara halus.
4. Akhiri SETIAP jawabanmu dengan satu pertanyaan pancingan pendek (call to action santai) agar obrolan berlanjut.
5. Gunakan format cetak tebal (*) pada nama fitur/produk penting.
6. JANGAN mengarang fitur atau harga yang tidak ada di Data SOP/Katalog.

OUTPUT KAMU WAJIB BERUPA RAW JSON DENGAN SKEMA BERIKUT:
{
  "reply_text": "Isi balasanmu yang mematuhi Aturan Emas di atas.",
  "lead_status": "baru" | "prospect" | "hot_prospek" | "deal" | "closing" | "gagal",
  "objection_reason": "PILIH SALAH SATU DARI: [' . $customObjections . '] atau isi \"null\" jika transaksi lancar",
  "ads_source": "Ekstrak nama promo/iklan dari chat pertama pelanggan, misal \'Promo IG\' atau \'Organik\'.",
  "chat_summary": "Buat 1 kalimat singkat (maks 10 kata) yang menyimpulkan inti percakapan prospek ini.",
  "lead_score": Isi dengan angka 1 sampai 100 yang menilai probabilitas closing,
  "buyer_character": "PILIH KATEGORI KEPRIBADIAN: "To The Point" | "Banyak Tanya" | "Ragu-Ragu" | "Skeptis" | "Ramah" (Pilih 1 kategori yang paling mencerminkan psikologi chat pelanggan saat ini)"
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

        // RETURN JSON KE N8N
        return response()->json([
            'knowledge' => $knowledge,
            'wablas_api_key' => $member->wablas_api_key,
            'wablas_secret_key' => $member->wablas_secret_key,
            'history' => $formattedHistory,
            'is_ai_active' => $session->is_ai_active,
            'is_command' => in_array($pesan, ['#s', '#c']),
            'project_images' => [] 
        ]);
    }

    // ====================================================================
    // 2. FUNGSI UNTUK MENYIMPAN HISTORY CHAT 
    // ====================================================================
    public function saveHistory(Request $request) {
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
                'customer_wa' => $cleanPhone, // Lebih baik simpan yang clean
                'user_message' => $request->user_message ?? $request->message ?? '',
                'ai_response' => trim($aiResponse),
            ]);

            // ==========================================================
            // --- DIPERBAIKI: TAMBAH +1 KE USAGE COUNT TANPA SYARAT ---
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