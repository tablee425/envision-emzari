<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiluentColumnToPiggings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('piggings', function($table) {
            $table->integer('diluent')->after('biocide_vol')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('piggings', function ($table) {
            $table->dropColumn('diluent');
        });
    }
}
