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
    // --- FITUR SIMPAN LINK & SYNC GOOGLE SHEETS (DYNAMIC CATALOG) ---
    // ====================================================================
    public function syncGoogleSheet(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'google_sheet_url' => 'required|url'
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        $originalUrl = $request->google_sheet_url;

        // 1. AMANKAN DAN SIMPAN LINK KE DATABASE (JALUR PAKSA / EXPLICIT SAVE)
        // Bypass perlindungan Mass Assignment agar URL dijamin 100% masuk ke database
        $pk = \App\Models\ProductKnowledge::where('user_id', $user->id)->first();
        
        if ($pk) {
            $pk->google_sheet_url = $originalUrl;
            $pk->save();
        } else {
            $newPk = new \App\Models\ProductKnowledge();
            $newPk->user_id = $user->id;
            $newPk->google_sheet_url = $originalUrl;
            $newPk->save();
        }

        // 2. BERSIHKAN & UBAH URL KE FORMAT EXPORT CSV PUBLIC GOOGLE
        $csvUrl = preg_replace('/\/edit.*$/', '/export?format=csv', $originalUrl);
        
        // Jika spreadsheet memiliki spesifik sheet/tab (parameter gid)
        if (preg_match('/[#&]gid=([0-9]+)/', $originalUrl, $matches)) {
            $csvUrl .= '&gid=' . $matches[1];
        }

        try {
            // 3. MULAI DOWNLOAD DATA DARI GOOGLE
            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($csvUrl);

            if ($response->successful()) {
                $csvData = $response->body();
                
                // Pecah teks CSV menjadi baris array PHP
                $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
                
                if (count($rows) < 2) {
                    return back()->with('success', 'Link Google Sheet berhasil disimpan, namun data sheet kosong atau header kolom tidak ditemukan.');
                }

                // Ambil baris pertama sebagai Header Nama Kolom
                $headers = array_shift($rows); 
                $headers = array_map('trim', $headers); // Bersihkan spasi kosong di nama kolom

                // 4. BERSIHKAN DATA KATALOG LAMA MILIK USER INI (REPLACE FULL)
                \App\Models\DynamicCatalog::where('user_id', $user->id)->delete();

                // 5. MASUKKAN DATA BARU HASIL SYNC
                $insertCount = 0;
                foreach ($rows as $row) {
                    // Lewati jika baris benar-benar kosong
                    if (empty(implode('', $row))) continue; 

                    // Petakan nama kolom dengan nilainya masing-masing
                    $rowData = [];
                    foreach ($headers as $index => $headerName) {
                        $rowData[$headerName] = trim($row[$index] ?? '');
                    }

                    // Simpan objek data ke database dalam format JSON bunglon
                    \App\Models\DynamicCatalog::create([
                        'user_id' => $user->id,
                        'raw_data' => $rowData
                    ]);
                    $insertCount++;
                }

                return back()->with('success', "Link berhasil disimpan & $insertCount data produk sukses disinkronisasikan ke database!");
            } else {
                return back()->with('error', 'Link gagal disimpan ke sistem. Server Google menolak akses. Pastikan status link Sheets diatur ke "Anyone with the link (Siapa saja yang memiliki link)".');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Link gagal disimpan akibat kesalahan sistem: ' . $e->getMessage());
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