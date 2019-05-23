<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNullableForScheduledBatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('injections', function (Blueprint $table) {
            DB::statement('ALTER TABLE `injections` MODIFY `scheduled_batches` INTEGER NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('injections', function (Blueprint $table) {
            DB::statement('ALTER TABLE `injections` MODIFY `scheduled_batches` INTEGER NOT NULL;');
        });
    }
}
