<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID Member pemilik AI
            $table->string('customer_wa'); // Nomor WA Pelanggan (Customer)
            $table->text('user_message'); // Pesan dari Pelanggan
            $table->text('ai_response'); // Balasan dari AI (Groq)
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('chat_histories');
    }
};