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
        Schema::table('bot_logs', function (Blueprint $table) {
            $table->integer('prompt_tokens')->default(0)->after('status'); // Token pertanyaan
            $table->integer('completion_tokens')->default(0)->after('prompt_tokens'); // Token jawaban
        });
    }
    
    public function down()
    {
        Schema::table('bot_logs', function (Blueprint $table) {
            $table->dropColumn(['prompt_tokens', 'completion_tokens']);
        });
    }
};
