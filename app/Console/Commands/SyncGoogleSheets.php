<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductKnowledge;
use App\Models\DynamicCatalog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncGoogleSheets extends Command
{
    // Nama perintah yang akan dijalankan di terminal
    protected $signature = 'catalog:sync-sheets';

    // Deskripsi perintah
    protected $description = 'Auto-sync semua data Google Sheets ke tabel Dynamic Catalog di background';

    public function handle()
    {
        // 1. Cari semua member yang sudah pernah menyimpan Link Google Sheets
        $pks = ProductKnowledge::whereNotNull('google_sheet_url')
                               ->where('google_sheet_url', '!=', '')
                               ->get();

        $this->info("Memulai Auto-Sync untuk " . $pks->count() . " toko...");

        foreach ($pks as $pk) {
            $url = $pk->google_sheet_url;
            
            // 2. Ubah URL menjadi format CSV
            $csvUrl = preg_replace('/\/edit.*$/', '/export?format=csv', $url);
            if (preg_match('/[#&]gid=([0-9]+)/', $url, $matches)) {
                $csvUrl .= '&gid=' . $matches[1];
            }

            try {
                // 3. Tarik data tanpa menunggu lama (timeout 30 detik agar tidak hang)
                $response = Http::timeout(30)->get($csvUrl);

                if ($response->successful()) {
                    $csvData = $response->body();
                    $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
                    
                    if (count($rows) < 2) continue; // Abaikan jika sheet kosong

                    $headers = array_map('trim', array_shift($rows));
                    
                    // 4. Hapus katalog lama user ini
                    DynamicCatalog::where('user_id', $pk->user_id)->delete();

                    // 5. Masukkan data baru
                    $insertCount = 0;
                    foreach ($rows as $row) {
                        if (empty(implode('', $row))) continue; 
                        
                        $rowData = [];
                        foreach ($headers as $index => $headerName) {
                            $rowData[$headerName] = trim($row[$index] ?? '');
                        }

                        DynamicCatalog::create([
                            'user_id' => $pk->user_id,
                            'raw_data' => $rowData
                        ]);
                        $insertCount++;
                    }
                    
                    $this->info("✅ User ID {$pk->user_id}: Sync Berhasil ({$insertCount} item).");
                } else {
                    $this->error("❌ User ID {$pk->user_id}: Gagal akses Google Sheets.");
                }

            } catch (\Exception $e) {
                $this->error("⚠️ User ID {$pk->user_id}: Error " . $e->getMessage());
                // Catat ke error log laravel agar kita bisa investigasi
                Log::error("Gagal Auto-Sync Sheets User ID {$pk->user_id}: " . $e->getMessage());
            }
        }

        $this->info("Auto-Sync Selesai!");
    }
}