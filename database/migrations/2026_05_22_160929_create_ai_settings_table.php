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
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            // Kolom relasi untuk mengenali ini pengaturan milik bot/instance yang mana
            $table->string('device_id')->unique(); 
            
            // Kolom pengaturan AI
            $table->string('ai_provider')->default('gemini');
            $table->string('ai_model')->default('gemini-flash-latest');
            $table->string('deepinfra_api_key')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
