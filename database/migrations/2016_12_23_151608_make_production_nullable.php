<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeProductionNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production', function ($table) {
            $table->date('date')->nullable()->change(); 
            $table->decimal('hours_on')->nullable()->change();
            $table->decimal('avg_oil')->nullable()->change();   // Average are DAILY
            $table->decimal('avg_gas')->nullable()->change();
            $table->decimal('avg_water')->nullable()->change();
            $table->integer('target_ppm')->default(null)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production', function ($table) {
            $table->date('date')->nullable(false)->change(); 
            $table->decimal('hours_on')->nullable(false)->change();
            $table->decimal('avg_oil')->nullable(false)->change();   // Average are DAILY
            $table->decimal('avg_gas')->nullable(false)->change();
            $table->decimal('avg_water')->nullable(false)->change();
            $table->integer('target_ppm')->default(0)->nullable(false)->change();
        });
    }
}
