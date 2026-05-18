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
    Schema::create('tracking_integrations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('provider'); // meta, google_analytics, google_ads, tiktok, webhook
        $table->string('pixel_id')->nullable(); // ID Pixel / Measurement ID / Tag ID
        $table->text('access_token')->nullable(); // Token API / Secret Key
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_integrations');
    }
};
