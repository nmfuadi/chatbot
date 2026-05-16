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
        Schema::table('lead_analytics', function (Blueprint $table) {
            // Menambahkan kolom tepat setelah kolom 'sumber_iklan'
            $table->text('chat_summary')->nullable()->after('sumber_iklan');
            $table->integer('lead_score')->default(0)->after('chat_summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_analytics', function (Blueprint $table) {
            // Menghapus kolom jika sewaktu-waktu dilakukan rollback
            $table->dropColumn(['chat_summary', 'lead_score']);
        });
    }
};