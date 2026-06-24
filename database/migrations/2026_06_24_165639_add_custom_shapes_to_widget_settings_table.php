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
        $table->string('widget_shape')->default('circle')->after('widget_position');
        $table->string('widget_icon')->default('chat')->after('widget_shape');
        $table->string('widget_text')->nullable()->after('widget_icon');
    });
}

public function down()
{
    Schema::table('widget_settings', function (Blueprint $table) {
        $table->dropColumn(['widget_shape', 'widget_icon', 'widget_text']);
    });
}
};
