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
    Schema::create('catalogs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Relasi ke mitra
        $table->string('item_name'); // Nama (Kamar/Barang/Layanan)
        $table->decimal('price', 15, 2)->default(0); // Harga
        $table->integer('stock')->default(0); // Stok / Ketersediaan
        $table->text('description')->nullable(); // Penjelasan singkat (opsional)
        $table->boolean('is_active')->default(true); // Status aktif/tidak
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogs');
    }
};
