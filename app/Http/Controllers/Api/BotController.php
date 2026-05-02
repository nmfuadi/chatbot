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
            'device_id' => 'required',
            'customer_phone' => 'required',
            'message' => 'nullable|string'
        ]);

        $member = User::where('wablas_device_id', $request->device_id)->first();

        if (!$member) {
            return response()->json(['error' => 'Member tidak ditemukan'], 404);
        }

        // --- LOGIKA HOLD/CONTINUE AI ---
        $session = ChatSession::firstOrCreate(
            ['user_id' => $member->id, 'customer_phone' => $request->customer_phone],
            ['customer_name' => $request->customer_name ?? 'Customer Baru', 'is_ai_active' => true]
        );

        $pesan = strtolower(trim($request->message));
        if ($pesan === '#s') {
            $session->update(['is_ai_active' => false]);
        } elseif ($pesan === '#c') {
            $session->update(['is_ai_active' => true]);
        }

        // ====================================================================
        // --- TAMBAHAN BARU: LOGIKA PENGECEKAN SUBSCRIPTION & KUOTA AI ---
        // ====================================================================
        $activeSub = Subscription::where('user_id', $member->id)
                        ->where('status', 'active')
                        ->where('ends_at', '>', now())
                        ->with('plan')
                        ->first();

        if (!$activeSub) {
            // Jika tidak punya paket aktif, paksa AI mati
            $session->update(['is_ai_active' => false]);
        } else {
            // Ambil limit dari paket (misal 0 atau -1 berarti unlimited)
            $maxMessages = $activeSub->plan->max_messages ?? 0;
            
            if ($maxMessages > 0) {
                // Hitung riwayat chat sejak masa paket ini dimulai
                $usageCount = ChatHistory::where('user_id', $member->id)
                                ->where('created_at', '>=', $activeSub->starts_at)
                                ->count();

                if ($usageCount >= $maxMessages) {
                    // Kuota habis! Ubah status langganan menjadi expired
                    $activeSub->update(['status' => 'expired']);
                    $member->update(['subscription_status' => 'expired']);
                    
                    // Terbitkan invoice baru secara otomatis
                    PaymentController::generateRenewalInvoice($member);
                    
                    \Log::info("BotController: Member {$member->id} kehabisan kuota ({$maxMessages} pesan). Langganan di-expired-kan & AI dihentikan.");
                    
                    // Paksa matikan AI meskipun member mencoba ketik #c sebelumnya
                    $session->update(['is_ai_active' => false]);
                }
            }
        }
        // ====================================================================

        // Ambil SOP
        $knowledge = ProductKnowledge::where('user_id', $member->id)
                        ->pluck('content')->implode("\n\n");

        if(empty($knowledge)) $knowledge = "Tidak ada SOP khusus.";

        // --- INJEKSI DATA KATALOG (SUDAH DIUPDATE MENGAMBIL SEMUA FOTO) ---
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

                // Mengambil SEMUA foto dari katalog dan memisahkannya dengan garis vertikal (|)
                if ($item->images->count() > 0) {
                    $allImages = $item->images->map(function($img) {
                        return asset('storage/' . $img->image_path);
                    })->implode(' | ');
                    
                    $knowledge .= "  KODE GAMBAR: [GAMBAR: {$allImages}]\n";
                }
            }

            // --- ATURAN SISTEM TRIGGER FOTO (KATALOG + ANTI SPAM) ---
            $knowledge .= "\n=== ATURAN SISTEM PENGIRIMAN GAMBAR (PENTING) ===\n";
            $knowledge .= "1. Jika customer meminta foto/gambar (contoh: 'minta foto', 'spill kamar', 'lihat dong'), kamu WAJIB menyertakan KODE GAMBAR dari data di atas di paling awal kalimat balasanmu (contoh: [GAMBAR: url1 | url2]).\n";
            $knowledge .= "2. JANGAN PERNAH meringkas atau mengubah link di dalam KODE GAMBAR. Copy-paste persis apa yang ada di data.\n";
            $knowledge .= "3. Jika customer TIDAK meminta foto (misal hanya bilang 'fotonya bagus', 'sudah lihat', atau bertanya hal lain), DILARANG KERAS menyertakan KODE GAMBAR.\n";
            $knowledge .= "4. Jika customer meminta foto lainnya (misal bilang 'ada foto yang lain', 'foto selain ini', atau pertanyaan lain seputar minta kirim foto lagi), JANGAN KIRIM KODE GAMBAR LAGI! Katakan saja: 'Aku sudah mengirim semua foto seluruh fasilitas dan kamar secara detail kak (tidak ada pengiriman foto ulang)'.\n";

            // ... (kode sebelumnya)

        // --- ATURAN SISTEM TRIGGER FOTO (KATALOG + ANTI SPAM) ---
        // (kode aturan foto Anda yang sudah ada di sini...)

        // ====================================================================
        // --- TAMBAHAN BARU: ATURAN BATASAN KONTEKS (PERHALUS / CLOSING) ---
        // ====================================================================
        $knowledge .= "\n=== BATASAN KONTEKS & PERINTAH AUTO-STOP ===\n";
        $knowledge .= "Kamu adalah Asisten Bisnis profesional. Tolong perhatikan riwayat obrolan sebelumnya. Jika customer membahas hal yang SAMA SEKALI TIDAK RELEVAN dengan bisnis/produk (seperti ngobrol santai, curhat, minta coding, dll), ikuti 2 tahapan ini secara ketat:\n";
        
        $knowledge .= "TAHAP 1 (Peringatan & Closing Statement): \n";
        $knowledge .= "Jika customer baru pertama kali melenceng dari topik, tolak dengan sangat sopan dan berikan closing statement. \n";
        $knowledge .= "Contoh balasan Tahap 1: 'Maaf kak, aku hanya asisten virtual untuk melayani pesanan dan info produk. Jika sudah tidak ada yang bisa aku bantu seputar produk kami, aku izin mengakhiri sesi chat ini ya kak 🙏'\n\n";

        $knowledge .= "TAHAP 2 (Eksekusi Penghentian): \n";
        $knowledge .= "Jika di chat selanjutnya customer MASIH membalas hal di luar konteks, atau sekadar membalas closing statement-mu tanpa ada niat bertanya soal produk, kamu WAJIB membalas HANYA dengan SATU KATA ini: [AUTO_STOP]\n";
        $knowledge .= "PENTING: Pada Tahap 2, JANGAN tambahkan kata maaf atau kata-kata lainnya. CUKUP KETIK: [AUTO_STOP]\n";
        // ====================================================================
        }

        // --- LOGIKA HISTORY CHAT ---
        $histories = ChatHistory::where('user_id', $member->id)
            ->where('customer_wa', $request->customer_phone)
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
            'is_ai_active' => $session->is_ai_active, // <-- Jika kuota habis, ini akan terkirim FALSE ke n8n
            'is_command' => in_array($pesan, ['#s', '#c']),
            'project_images' => [] // Dikosongkan karena kita sekarang 100% pakai Katalog
        ]);
    }

    // ====================================================================
    // 2. FUNGSI UNTUK MENYIMPAN HISTORY CHAT 
    // ====================================================================
    // ====================================================================
    // 2. FUNGSI UNTUK MENYIMPAN HISTORY CHAT 
    // ====================================================================
    public function saveHistory(Request $request) {
        $member = User::where('wablas_device_id', $request->device_id)->first();
        
        if ($member) {
            $aiResponse = $request->ai_response ?? '';

            // --- DETEKSI SANDI RAHASIA DARI AI ---
            if (str_contains($aiResponse, '[AUTO_STOP]')) {
                
                // 1. Matikan sesi AI untuk nomor WhatsApp customer ini
                \App\Models\ChatSession::where('user_id', $member->id)
                    ->where('customer_phone', $request->customer_phone)
                    ->update(['is_ai_active' => false]);
                
                // 2. Bersihkan sandi rahasia dari teks agar tidak masuk ke database (biar terlihat rapi)
                $aiResponse = str_replace('[AUTO_STOP]', '', $aiResponse);
                
                \Log::info("KILL SWITCH AKTIF: Percakapan keluar konteks. AI dimatikan untuk nomor {$request->customer_phone}.");
            }

            // --- SIMPAN KE DATABASE SEPERTI BIASA ---
            ChatHistory::create([
                'user_id' => $member->id,
                'customer_wa' => $request->customer_phone,
                'user_message' => $request->user_message ?? $request->message ?? '',
                'ai_response' => trim($aiResponse),
            ]);
        }
        
        return response()->json(['status' => 'success']);
    }
}