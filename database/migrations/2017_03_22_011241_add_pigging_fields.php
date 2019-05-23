<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPiggingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('piggings', function (Blueprint $table) {
            $table->integer('line_pressure')->after('od')->nullable();
            $table->integer('pressure_switch')->after('od')->nullable();
            $table->string('line_type')->after('od');
            $table->integer('MOP')->after('frequency')->nullable();
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
            $table->dropColumn('line_type');
            $table->dropColumn('line_pressure');
            $table->dropColumn('pressure_switch');
            $table->dropColumn('MOP');
        });
    }
}
