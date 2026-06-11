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
        Schema::create('commerce_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', ['goods', 'booking'])->default('goods'); // Tipe: Barang fisik atau Jadwal Sewa
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->integer('price');
            
            // Khusus tipe "goods" (barang)
            $table->integer('weight_grams')->default(0); // Untuk hitung ongkir
            $table->integer('stock')->default(0); // Stok barang fisik
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commerce_catalogs');
    }
};
