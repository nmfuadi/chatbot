<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductKnowledge;
use App\Models\ChatHistory;
use App\Models\ChatSession;
use App\Models\Catalog;
use Illuminate\Support\Facades\Storage; // WAJIB TAMBAHKAN INI

class BotController extends Controller {
    
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

        // Ambil SOP
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
                $hargaRupiah = "Rp " . number_format($item->price, 0, ',', '.');
                $knowledge .= "- Nama: {$item->item_name} | Harga: {$hargaRupiah} | Stok: {$item->stock}\n";
                if ($item->description) $knowledge .= "  Deskripsi: {$item->description}\n";

                if ($item->images->count() > 0) {
                    $imageUrl = asset('storage/' . $item->images->first()->image_path);
                    $knowledge .= "  URL Foto: {$imageUrl}\n";
                }
            }

            $knowledge .= "\n=== INSTRUKSI KHUSUS KIRIM GAMBAR KATALOG ===\n";
            $knowledge .= "Jika pelanggan meminta foto produk di atas, gunakan format: [GAMBAR: url_foto]\n";
        }

        // --- AWAL BACA FOLDER PROJECT MANUAL (SUDAH DIPINDAH KE ATAS RETURN) ---
        $directories = Storage::disk('public')->directories('projects');
        
        if (!empty($directories)) {
            $knowledge .= "\n\n=== DATA FOTO PROJECT (DARI FOLDER MANUAL) ===\n";
            $knowledge .= "Berikut adalah daftar nama kost/project beserta kumpulan link foto-fotonya:\n";
            
            foreach ($directories as $dir) {
                $projectName = str_replace('projects/', '', $dir);
                $projectNameClean = ucwords(str_replace('-', ' ', $projectName));
                
                $files = Storage::disk('public')->files($dir);
                $imageUrls = [];
                
                foreach ($files as $file) {
                    if (preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                        $imageUrls[] = asset('storage/' . $file);
                    }
                }
                
                if (count($imageUrls) > 0) {
                    $knowledge .= "- Nama Project: {$projectNameClean}\n";
                    $knowledge .= "  Kumpulan URL Foto: " . implode(" | ", $imageUrls) . "\n";
                }
            }

            $knowledge .= "\n=== INSTRUKSI MUTLAK PENGIRIMAN GAMBAR ===\n";
            $knowledge .= "1. Jika customer meminta foto project/kost, kamu WAJIB memberikan SEMUA URL foto yang tersedia di daftar tanpa terkecuali!\n";
            $knowledge .= "2. DILARANG KERAS meringkas, menyortir, atau menyembunyikan link foto. Kamu adalah sistem otomatis, berikan KESELURUHAN link.\n";
            $knowledge .= "3. WAJIB gunakan format tag kurung siku dengan pemisah garis vertikal (|) di AWAL balasan.\n";
            $knowledge .= "4. Format: [GAMBAR: url_foto_1 | url_foto_2 | url_foto_3 | url_foto_4 | dan seterusnya...]\n";
        }

        // Ambil History
        $histories = ChatHistory::where('user_id', $member->id)
            ->where('customer_wa', $request->customer_phone)
            ->where('created_at', '>=', now()->subDay())
            ->latest()->take(5)->get()->reverse();

        $formattedHistory = [];
        foreach ($histories as $h) {
            $formattedHistory[] = ['role' => 'user', 'content' => $h->user_message];
            if ($h->ai_response) $formattedHistory[] = ['role' => 'assistant', 'content' => $h->ai_response];
        }

        // RETURN SEKARANG ADA DI PALING BAWAH
        return response()->json([
            'knowledge' => $knowledge,
            'wablas_api_key' => $member->wablas_api_key,
            'wablas_secret_key' => $member->wablas_secret_key,
            'history' => $formattedHistory,
            'is_ai_active' => $session->is_ai_active,
            'is_command' => in_array($pesan, ['#s', '#c'])
        ]);
    }

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