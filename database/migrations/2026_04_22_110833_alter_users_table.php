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
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone')->nullable();
        $table->enum('role', ['admin', 'member'])->default('member');
        $table->enum('status', ['pending', 'active'])->default('pending');
        $table->boolean('force_password_change')->default(false);
        // Data Wablas dari Admin
        $table->string('wablas_api_key')->nullable();
        $table->string('wablas_secret_key')->nullable();
        $table->string('wablas_device_id')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
