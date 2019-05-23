<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartAndEndLocationsToPigging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('piggings', function (Blueprint $table) {
            $table->renameColumn('location_id', 'start_location_id');
            $table->integer('end_location_id')->after('location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('piggings', function (Blueprint $table) {
            $table->renameColumn('start_location_id', 'location_id');
            $table->dropColumn('end_location_id');
        });
    }
}
