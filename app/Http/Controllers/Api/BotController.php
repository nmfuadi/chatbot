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

        // --- AWAL LOGIKA HOLD/CONTINUE AI ---
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

        // --- AWAL BACA FOLDER PROJECT MANUAL (KAMUS UNTUK N8N) ---
        $directories = Storage::disk('public')->directories('projects');
        $projectImages = []; 
        
        if (!empty($directories)) {
            $knowledge .= "\n\n=== DATA PROJECT & KODE FOTO ===\n";
            $knowledge .= "Jika customer meminta foto project, kamu HANYA PERLU menyisipkan KODE FOTO di awal balasan. JANGAN kirim link manual!\n";
            
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
                    $projectImages[$projectName] = $imageUrls; 
                    $knowledge .= "- Nama Project: {$projectNameClean} -> KODE FOTO: [FOTO: {$projectName}]\n";
                }
            }

            $knowledge .= "\nContoh balasan jika user minta foto: '[FOTO: griya-permata] Tentu kak, ini foto-fotonya. Ada yang ingin ditanyakan?'\n";
        }

        // --- AWAL LOGIKA HISTORY (YANG TADI SEMPAT TERHAPUS) ---
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
            'is_ai_active' => $session->is_ai_active,
            'is_command' => in_array($pesan, ['#s', '#c']),
            'project_images' => $projectImages
        ]);
    }
}