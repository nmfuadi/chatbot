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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            
            $table->string('status'); // 'pending', 'active', 'expired', 'cancelled'
            $table->timestamp('starts_at')->nullable(); // Tanggal mulai aktif
            $table->timestamp('ends_at')->nullable(); // Tanggal kedaluwarsa (misal +30 hari)
            
            // --- TRACKING PENGGUNAAN ---
            // Kita simpan di sini agar mudah dicek tanpa harus query tabel history terus menerus
            $table->integer('messages_used')->default(0); 
            
            $table->string('payment_id')->nullable(); // ID dari Payment Gateway (Midtrans/Xendit)
            $table->string('payment_url')->nullable(); // Link pembayaran
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
