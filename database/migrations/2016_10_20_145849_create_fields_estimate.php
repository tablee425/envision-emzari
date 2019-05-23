<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsEstimate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('injections', function (Blueprint $table) {
            $table->float('estimate_inventory')->nullable();
            $table->float('estimate_usage_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('injections', function (Blueprint $table) {
            $table->dropColumn('estimate_inventory');
            $table->dropColumn('estimate_usage_rate');
        });
    }
}
