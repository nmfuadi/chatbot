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
        Schema::table('product_knowledges', function (Blueprint $table) {
            // Menyimpan kata pemicu dalam bentuk string yang dipisah koma
            $table->text('catalog_trigger_words')->nullable()->after('content');
        });
    }
    
    public function down()
    {
        Schema::table('product_knowledges', function (Blueprint $table) {
            $table->dropColumn('catalog_trigger_words');
        });
    }
};
