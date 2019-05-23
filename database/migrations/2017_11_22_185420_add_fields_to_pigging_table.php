<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPiggingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('piggings', function (Blueprint $table) {
            $table->string('gauged', 3)->after('pig_number')->nullable()->default(null);
            $table->string('condition', 10)->after('gauged')->nullable()->default(null);
            $table->tinyInteger('wax')->nullable()->after('condition')->default(null);
            $table->decimal('length',8,2)->nullable()->after('license')->default(null);
            $table->decimal('thickness',8,1)->nullable()->after('od')->default(null);
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
            $table->dropColumn('gauged');
            $table->dropColumn('condition');
            $table->dropColumn('wax');
            $table->dropColumn('length');
            $table->dropColumn('thickness');
        });
    }
}
