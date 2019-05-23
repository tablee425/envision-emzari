<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePiggingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('piggings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id');
            $table->float('od')->nullable();
            $table->string('license')->nullable();
            $table->integer('frequency')->nullable();
            $table->date('scheduled_on')->nullable();
            $table->date('shipped_on')->nullable();
            $table->date('pulled_on')->nullable();
            $table->date('cancelled_on')->nullable();
            $table->string('pig_size')->nullable();
            $table->string('pig_number')->nullable();
            $table->float('corr_inh_vol')->nullable();
            $table->float('biocide_vol')->nullable();
            $table->float('water_vol')->nullable();
            $table->text('field_operator')->nullable();
            $table->text('comments')->nullable();
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
        Schema::drop('piggings');
    }
}
