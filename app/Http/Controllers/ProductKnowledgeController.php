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
}