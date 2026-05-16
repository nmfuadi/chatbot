<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_analytics', function (Blueprint $table) {
            // Menambahkan kolom chat_session_id setelah kolom id
            $table->unsignedBigInteger('chat_session_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('lead_analytics', function (Blueprint $table) {
            $table->dropColumn('chat_session_id');
        });
    }
};