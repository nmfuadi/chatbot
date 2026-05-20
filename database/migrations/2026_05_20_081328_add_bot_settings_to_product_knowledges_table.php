<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_knowledges', function (Blueprint $table) {
            // Pengaturan Identitas & Gaya
            $table->string('ai_name')->default('sobat')->nullable();
            $table->string('customer_call')->default('Kakak')->nullable();
            $table->string('gaya_bahasa')->default('santai')->nullable(); // santai, formal, gaul_digital
            $table->string('gaya_berpikir')->default('strict_sop')->nullable(); // strict_sop, flexible_knowledge
            
            // Pengaturan Perilaku Chat
            $table->string('primary_objective')->default('soft_selling')->nullable(); // hard_selling, soft_selling, customer_service
            $table->string('reply_length')->default('singkat')->nullable(); // singkat, sedang, detail
            $table->string('fallback_behavior')->default('arah kan_cs')->nullable(); // arahkan_cs, jujur_pivot
            $table->string('use_emoji')->default('banyak_emoji')->nullable(); // banyak_emoji, tanpa_emoji
        });
    }

    public function down(): void
    {
        Schema::table('product_knowledges', function (Blueprint $table) {
            $table->dropColumn([
                'ai_name', 'customer_call', 'gaya_bahasa', 'gaya_berpikir',
                'primary_objective', 'reply_length', 'fallback_behavior', 'use_emoji'
            ]);
        });
    }
};