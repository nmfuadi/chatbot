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
        Schema::table('widget_settings', function (Blueprint $table) {
            // Tambahkan kolom widget_position, default di kanan bawah
            $table->string('widget_position')->default('bottom-right')->after('greeting_text');
        });
    }
    
    public function down()
    {
        Schema::table('widget_settings', function (Blueprint $table) {
            $table->dropColumn('widget_position');
        });
    }
};
