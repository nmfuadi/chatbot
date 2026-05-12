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
    Schema::create('bot_logs', function (Blueprint $table) {
        $table->id();
        $table->string('instance_name')->nullable(); // Nama bot (contoh: member_8)
        $table->string('phone_number')->nullable();  // Nomor user yang chat
        $table->string('status'); // 'success' atau 'error'
        $table->integer('processing_time_ms')->nullable(); // Waktu yang dihabiskan AI (milidetik)
        $table->text('error_message')->nullable(); // Jika ada error
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_logs');
    }
};
