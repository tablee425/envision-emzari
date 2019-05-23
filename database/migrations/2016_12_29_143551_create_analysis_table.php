<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id');
            $table->date('date'); 
            $table->integer('corrosion_residuals');
            $table->integer('scale_residuals');
            $table->integer('water_qualities');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->unique(['date', 'location_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('analysis');
    }
}
