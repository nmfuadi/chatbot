<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\TrackingIntegration;
use Illuminate\Support\Facades\Log;

class TrackingService
{
    /**
     * Memetakan status Kanban TERA.AI ke Standard Event FB/Tiktok/Google
     */
    public static function mapEventName($statusProspek)
    {
        $map = [
            'baru'        => 'Contact',
            'prospect'    => 'Lead',
            'hot_prospek' => 'Lead',
            'deal'        => 'InitiateCheckout',
            'closing'     => 'Purchase',
            'gagal'       => 'Cancel'
        ];

        return $map[$statusProspek] ?? 'Other';
    }

    /**
     * Mengeksekusi pengiriman data secara background ke n8n
     */
    public static function dispatch($userId, $customerPhone, $statusProspek, $value = 0)
    {
        // 1. Cek apakah member punya integrasi aktif
        $integrations = TrackingIntegration::where('user_id', $userId)
                                         ->where('is_active', true)
                                         ->get();

        if ($integrations->isEmpty()) {
            return false;
        }

        // 2. Hash Nomor HP menggunakan SHA256 (Standar CAPI Meta & TikTok)
        $cleanPhone = preg_replace('/[^0-9]/', '', $customerPhone);
        // Pastikan format diawali kode negara (misal 62) tanpa +
        if (substr($cleanPhone, 0, 1) == '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $hashedPhone = hash('sha256', $cleanPhone);

        // 3. Konversi Status ke Nama Event
        $eventName = self::mapEventName($statusProspek);

        // 4. Rakit Payload
        $payload = [
            'event_name' => $eventName,
            'event_id' => 'evt_' . time() . '_' . rand(1000, 9999), // Mencegah event ganda terhitung 2x
            'timestamp' => time(),
            'user_data' => [
                'ph' => $hashedPhone, // Hashed Phone (Aman untuk privasi)
            ],
            'custom_data' => [
                'value' => (int) $value,
                'currency' => 'IDR'
            ],
            'integrations' => $integrations->toArray()
        ];

        // 5. Tembak ke Webhook Router n8n (Ubah URL ini sesuai URL Webhook n8n Kakak)
        $n8nWebhookUrl = 'https://n8n.domainkakak.com/webhook/tera-tracking-router';

        try {
            // Gunakan timeout singkat agar tidak membebani respon bot jika n8n down
            Http::timeout(3)->withOptions(['verify' => false])->post($n8nWebhookUrl, $payload);
            return true;
        } catch (\Exception $e) {
            Log::error("Tracking Dispatch Error: " . $e->getMessage());
            return false;
        }
    }
}