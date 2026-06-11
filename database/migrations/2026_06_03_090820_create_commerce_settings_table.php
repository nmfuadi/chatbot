<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commerce_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_commerce_active')->default(false); // Tombol On/Off fitur commerce
            $table->enum('catalog_mode', ['use_catalog', 'no_catalog'])->default('use_catalog');
            
            // Pengaturan Payment Gateway
            $table->enum('pg_provider', ['duitku', 'paymenku'])->default('duitku');
            $table->string('pg_merchant_code')->nullable();
            $table->string('pg_api_key')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commerce_settings');
    }
};
