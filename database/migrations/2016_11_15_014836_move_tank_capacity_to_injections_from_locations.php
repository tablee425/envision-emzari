<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveTankCapacityToInjectionsFromLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function ($table) {
            $table->dropColumn('tank_capacity');
        });

        Schema::table('injections', function (Blueprint $table) {
            $table->integer('tank_capacity')->nullable()->after('target_ppm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('injections', function ($table) {
            $table->dropColumn('tank_capacity');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->integer('tank_capacity')->nullable()->after('unit_of_measure');
        });
    }
}
