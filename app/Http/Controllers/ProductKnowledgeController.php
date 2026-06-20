<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductKnowledgeController extends Controller
{
    // ... (fungsi kakak yang lain) ...

    public function scrapeWebsite(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $targetUrl = $request->url;
        
        try {
            // Menggunakan Jina AI Reader untuk mengubah Web HTML menjadi Teks AI (Markdown) murni
            $response = Http::timeout(30)
                ->withHeaders(['Accept' => 'text/plain']) // Meminta balasan berupa teks murni
                ->get('https://r.jina.ai/' . $targetUrl);

            if ($response->successful()) {
                $cleanText = $response->body();
                
                // Opsional: Membuang teks bawaan Jina jika ada
                $cleanText = preg_replace('/Title:.*\nURL:.*\n/m', '', $cleanText);

                return response()->json([
                    'success' => true,
                    'text' => trim($cleanText),
                    'message' => 'Website berhasil dibaca oleh AI!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Website menolak untuk diakses atau dilindungi sistem anti-bot.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu habis / Server gagal merespon: ' . $e->getMessage()
            ]);
        }
    }

    // ====================================================================
    // --- FITUR SYNC GOOGLE SHEETS KE DATABASE (DYNAMIC CATALOG) ---
    // ====================================================================
    public function syncGoogleSheet(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'google_sheet_url' => 'required|url'
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        $originalUrl = $request->google_sheet_url;

        // 1. Simpan URL-nya ke tabel
        \App\Models\ProductKnowledge::updateOrCreate(
            ['user_id' => $user->id],
            ['google_sheet_url' => $originalUrl]
        );

        // 2. Ubah URL Google Sheet biasa menjadi URL Export CSV
        // Contoh: https://docs.google.com/spreadsheets/d/1ABC/edit#gid=0
        // Menjadi: https://docs.google.com/spreadsheets/d/1ABC/export?format=csv&gid=0
        $csvUrl = preg_replace('/\/edit.*$/', '/export?format=csv', $originalUrl);
        
        // Jika ada spesifik sheet (gid), tambahkan ke URL CSV-nya
        if (preg_match('/[#&]gid=([0-9]+)/', $originalUrl, $matches)) {
            $csvUrl .= '&gid=' . $matches[1];
        }

        try {
            // 3. Tarik data CSV dari Google
            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($csvUrl);

            if ($response->successful()) {
                $csvData = $response->body();
                
                // Parse CSV text ke dalam Array PHP
                $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
                
                if (count($rows) < 2) {
                    return back()->with('error', 'Google Sheet kosong atau header tidak ditemukan.');
                }

                $headers = array_shift($rows); // Ambil baris pertama sebagai Header (Nama Kolom)
                
                // Bersihkan header (hapus spasi berlebih atau karakter aneh)
                $headers = array_map('trim', $headers);

                // 4. Hapus katalog lama milik user ini, karena kita akan Replace Full
                \App\Models\DynamicCatalog::where('user_id', $user->id)->delete();

                // 5. Masukkan data baru ke database
                $insertCount = 0;
                foreach ($rows as $row) {
                    // Abaikan baris kosong
                    if (empty(implode('', $row))) continue; 

                    // Gabungkan Header dengan Value baris tersebut
                    $rowData = [];
                    foreach ($headers as $index => $headerName) {
                        $rowData[$headerName] = trim($row[$index] ?? '');
                    }

                    // Simpan sebagai JSON ke database
                    \App\Models\DynamicCatalog::create([
                        'user_id' => $user->id,
                        'raw_data' => $rowData
                    ]);
                    $insertCount++;
                }

                return back()->with('success', "Berhasil! $insertCount data produk berhasil ditarik dan diperbarui dari Google Sheets.");
            } else {
                return back()->with('error', 'Gagal membaca Google Sheets. Pastikan akses link diatur ke "Anyone with the link".');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function showDynamicCatalog()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        
        // Ambil URL Google Sheet yang pernah di-save
        $pk = \App\Models\ProductKnowledge::where('user_id', $userId)->first();
        
        // Ambil semua data katalog dinamis milik user
        $catalogs = \App\Models\DynamicCatalog::where('user_id', $userId)->get();

        return view('member.dynamic-catalog', compact('pk', 'catalogs'));
    }
}