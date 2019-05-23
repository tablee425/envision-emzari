<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id');
            $table->date('date'); 
            // $table->integer('target_ppm')->default(0);
            $table->decimal('hours_on');
            $table->decimal('avg_oil');   // Average are DAILY
            $table->decimal('avg_gas');
            $table->decimal('avg_water');
            $table->timestamps();

            $table->index('location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('production');
    }
}
