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
                // Menyimpan tipe kepribadian pembeli hasil analisis AI
                $table->string('buyer_character')->nullable()->after('lead_score');
            });
        }

        public function down(): void
        {
            Schema::table('lead_analytics', function (Blueprint $table) {
                $table->dropColumn('buyer_character');
            });
        }
};
