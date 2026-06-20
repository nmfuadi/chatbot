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
        // Tabel ini bertindak sebagai "Bunglon"
        Schema::create('dynamic_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('raw_data'); // Menyimpan seluruh baris excel jadi 1 objek JSON
            $table->timestamps();
        });

        // Tambahkan kolom untuk menyimpan URL Google Sheet di tabel product_knowledges
        Schema::table('product_knowledges', function (Blueprint $table) {
            $table->string('google_sheet_url', 500)->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_catalogs');
    }
};
