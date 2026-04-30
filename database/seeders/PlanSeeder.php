<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Paket Uji Coba',
                'price' => 0,
                'max_messages' => 50,
                'is_unlimited_messages' => false,
                'max_sop_chars' => 500,
                'max_wa_numbers' => 1,
                'features' => [
                    'Masa Aktif 7 Hari',
                    'Limit 50 Pesan AI',
                    'Maksimal SOP 500 Karakter',
                    '1 Nomor WhatsApp',
                    'Support Standar'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'CS AI 1.0',
                'price' => 159000,
                'max_messages' => 1000,
                'is_unlimited_messages' => false,
                'max_sop_chars' => 3000,
                'max_wa_numbers' => 1,
                'features' => [
                    'Masa Aktif 30 Hari',
                    'Limit 1.000 Pesan AI',
                    'Maksimal SOP 3.000 Karakter',
                    '1 Nomor WhatsApp',
                    'Auto Follow Up Basic'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'CS AI 2.0',
                'price' => 299000,
                'max_messages' => 5000,
                'is_unlimited_messages' => false,
                'max_sop_chars' => 8000,
                'max_wa_numbers' => 2,
                'features' => [
                    'Masa Aktif 30 Hari',
                    'Limit 5.000 Pesan AI',
                    'Maksimal SOP 8.000 Karakter',
                    'Maksimal 2 Nomor WhatsApp',
                    'Prioritas Support',
                    'Fitur Katalog Produk'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'CS AI Unlimited',
                'price' => 599000,
                'max_messages' => 0, // 0 tapi ditandai unlimited
                'is_unlimited_messages' => true,
                'max_sop_chars' => 20000,
                'max_wa_numbers' => 5,
                'features' => [
                    'Masa Aktif 30 Hari',
                    'Unlimited Pesan AI',
                    'Maksimal SOP 20.000 Karakter',
                    'Maksimal 5 Nomor WhatsApp',
                    'Dedicated Account Manager',
                    'Akses Semua Fitur Premium'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}