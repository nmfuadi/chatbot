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
        Schema::table('product_knowledges', function (Blueprint $table) {
            $table->string('objection_reasons')->nullable()->default('ongkir_mahal, budget_kurang, kompetitor, slow_respon')->after('content');
            $table->text('lead_rule_baru')->nullable()->after('objection_reasons');
            $table->text('lead_rule_prospect')->nullable()->after('lead_rule_baru');
            $table->text('lead_rule_hot_prospek')->nullable()->after('lead_rule_prospect');
            $table->text('lead_rule_deal')->nullable()->after('lead_rule_hot_prospek');
            $table->text('lead_rule_closing')->nullable()->after('lead_rule_deal');
            $table->text('lead_rule_gagal')->nullable()->after('lead_rule_closing');
        });
    }
    
    public function down(): void
    {
        Schema::table('product_knowledges', function (Blueprint $table) {
            $table->dropColumn([
                'objection_reasons', 'lead_rule_baru', 'lead_rule_prospect', 
                'lead_rule_hot_prospek', 'lead_rule_deal', 'lead_rule_closing', 'lead_rule_gagal'
            ]);
        });
    }
};
