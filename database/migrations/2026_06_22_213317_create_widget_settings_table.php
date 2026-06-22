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
        Schema::create('widget_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Milik admin/owner
            $table->boolean('is_active')->default(false); // Toggle aktif/mati widget
            $table->string('primary_color')->default('#4F46E5'); // Warna kustom (default Indigo)
            $table->string('logo_path')->nullable(); // Logo kustom
            $table->string('greeting_text')->default('Halo! Silakan isi data di bawah untuk memulai obrolan.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_settings');
    }
};
