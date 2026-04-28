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
        $catalogs = Catalog::with('images')
                        ->where('user_id', $member->id)
                        ->where('is_active', true)
                        ->get();

        if ($catalogs->isNotEmpty()) {
            $knowledge .= "\n\n=== DATA KATALOG / KETERSEDIAAN SAAT INI ===\n";
            $knowledge .= "Berikut adalah data real-time harga, stok, dan link foto produk:\n";
            
            foreach ($catalogs as $item) {
                // Format harga ke Rupiah
                $hargaRupiah = "Rp " . number_format($item->price, 0, ',', '.');
                $knowledge .= "- Nama: {$item->item_name} | Harga: {$hargaRupiah} | Stok: {$item->stock}\n";
                
                if ($item->description) {
                    $knowledge .= "  Deskripsi: {$item->description}\n";
                }

                // Jika ada foto, berikan URL-nya ke AI
                if ($item->images->count() > 0) {
                    $imageUrl = asset('storage/' . $item->images->first()->image_path);
                    $knowledge .= "  URL Foto: {$imageUrl}\n";
                }
            }

            // INJEKSI INSTRUKSI PENGIRIMAN GAMBAR UNTUK AI
            $knowledge .= "\n=== INSTRUKSI KHUSUS KIRIM GAMBAR ===\n";
            $knowledge .= "Jika pelanggan memintamu untuk mengirimkan atau memperlihatkan foto/gambar suatu produk, kamu WAJIB menyertakan tag gambar di AWAL kalimat balasanmu menggunakan format ini: [GAMBAR: url_foto_produk]\n";
            $knowledge .= "Contoh jika user minta gambar: '[GAMBAR: https://domain.com/storage/catalogs/foto.jpg] Tentu kak, ini foto produknya. Apakah ada yang ingin ditanyakan lagi?'\n";
            $knowledge .= "JANGAN gunakan tag gambar jika user tidak memintanya secara spesifik.\n";
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


        
        // --- AWAL BACA FOLDER PROJECT MANUAL ---
        // Membaca semua sub-folder di dalam folder storage/app/public/projects
        $directories = Storage::disk('public')->directories('projects');
        
        if (!empty($directories)) {
            $knowledge .= "\n\n=== DATA FOTO PROJECT (DARI FOLDER MANUAL) ===\n";
            $knowledge .= "Berikut adalah daftar nama kost/project beserta kumpulan link foto-fotonya:\n";
            
            foreach ($directories as $dir) {
                // Mengubah nama folder "projects/kost-melati" menjadi "Kost Melati"
                $projectName = str_replace('projects/', '', $dir);
                $projectNameClean = ucwords(str_replace('-', ' ', $projectName));
                
                // Ambil semua file di dalam folder tersebut
                $files = Storage::disk('public')->files($dir);
                $imageUrls = [];
                
                foreach ($files as $file) {
                    // Hanya ambil file gambar (mencegah error kalau ada file lain)
                    if (preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                        $imageUrls[] = asset('storage/' . $file);
                    }
                }
                
                // Jika foldernya ada foto, beritahu AI
                if (count($imageUrls) > 0) {
                    $knowledge .= "- Nama Project: {$projectNameClean}\n";
                    $knowledge .= "  Kumpulan URL Foto: " . implode(" | ", $imageUrls) . "\n";
                }
            }

            // Instruksi ke AI untuk mengirim BANYAK foto sekaligus
            $knowledge .= "\n=== INSTRUKSI KHUSUS KIRIM MULTIPLE GAMBAR ===\n";
            $knowledge .= "Jika customer meminta foto sebuah project, kamu WAJIB menyisipkan tag gambar di AWAL balasanmu.\n";
            $knowledge .= "Karena fotonya banyak, gabungkan semua URL foto menggunakan karakter garis vertikal (|) di dalam tag.\n";
            $knowledge .= "Format wajib: [GAMBAR: url1 | url2 | url3]\n";
            $knowledge .= "Contoh balasanmu: '[GAMBAR: https://web.com/storage/1.jpg | https://web.com/storage/2.jpg] Tentu kak, ini foto-foto Kost Melatinya. Ada yang ingin ditanyakan?'\n";
        }
        // --- AKHIR BACA FOLDER PROJECT MANUAL ---


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