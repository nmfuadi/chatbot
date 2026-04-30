<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('plans', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Contoh: Uji Coba, CS AI 1.0, dll
        $table->decimal('price', 15, 2)->default(0); // Harga paket (0 untuk gratis)
        
        // --- LIMITASI PAKET ---
        $table->integer('max_messages')->default(0); // Batas pesan (misal: 1000). 0 bisa berarti unlimited, tapi kita pakai kolom terpisah untuk amannya
        $table->boolean('is_unlimited_messages')->default(false);
        $table->integer('max_sop_chars')->default(1000); // Batas karakter input SOP
        $table->integer('max_wa_numbers')->default(1); // Jumlah nomor WA yang diizinkan
        
        $table->json('features')->nullable(); // Untuk menyimpan list fitur seperti "Basic SOP", "Auto Follow Up", dll (format JSON)
        $table->boolean('is_active')->default(true); // Status apakah paket ini masih dijual
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
