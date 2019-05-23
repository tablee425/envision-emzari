<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('composition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id');
            $table->date('date');
            $table->decimal('iron', 8, 2);
            $table->decimal('manganese', 8, 2);
            $table->decimal('chloride', 8, 2);
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
        Schema::drop('composition');
    }
}
