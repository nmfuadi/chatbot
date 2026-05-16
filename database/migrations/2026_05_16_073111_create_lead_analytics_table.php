<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('phone'); // Nomor HP / JID Pelanggan
            $table->string('instance'); // Nama device/mesin WhatsApp member
            $table->string('status_prospek'); // baru, tanya_harga, hot_prospek, closing, gagal
            $table->string('alasan_batal')->nullable(); // ongkir_mahal, budget_kurang, dll
            $table->string('sumber_iklan')->default('Organik'); // Promo IG, FB Ads, dll
            $table->timestamps(); // Mencatat waktu interaksi (created_at)

            // Indexing untuk mempercepat query dashboard nantinya
            $table->index(['instance', 'status_prospek']);
            $table->index('sumber_iklan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_analytics');
    }
};