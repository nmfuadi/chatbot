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
        // 1. Bagian WA & OTP (Menggunakan kodemu yang sudah sangat bagus)
        $table->string('whatsapp_number')->unique()->nullable()->after('email');
        $table->string('otp_code', 6)->nullable()->after('whatsapp_number');
        $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
        $table->boolean('is_wa_verified')->default(false)->after('otp_expires_at');

        // 2. Bagian Profil Usaha (Tambahan baru)
        $table->string('business_name')->nullable()->after('is_wa_verified');
        $table->string('business_category')->nullable()->after('business_name');
        $table->string('business_address')->nullable()->after('business_category');
        $table->text('business_description')->nullable()->after('business_address');
        
        // 3. Bagian Status Langganan / Pembayaran
        $table->enum('subscription_status', ['unpaid', 'active', 'expired'])->default('unpaid')->after('business_description');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'whatsapp_number', 
            'otp_code', 
            'otp_expires_at', 
            'is_wa_verified',
            'business_name',
            'business_category',
            'business_address',
            'business_description',
            'subscription_status'
        ]);
    });
}
};
