<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('injections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id');
            $table->date('date');  // YYYY-MM
            $table->enum('type', ['BATCH', 'CONTINUOUS']);
            $table->string('based_on');
            $table->string('name')->nullable();
            $table->integer('days_in_month')->nullable();
            $table->string('vendor');
            $table->string('chemical_type');
            $table->string('cost_centre');
            $table->integer('target_frequency'); // days
            $table->integer('unit_cost');
            
            // Batch
            $table->float('batch_size')->nullable();
            $table->float('circulation_time');
            $table->float('diluent_required');
            $table->integer('scheduled_batches');
            $table->integer('batch_cost_center');
            $table->integer('batch_frequency');
            // Continuous
            $table->float('inventory_start');
            $table->float('inventory_end');
            $table->float('chemical_inventory');
            $table->float('chemical_start')->nullable();
            $table->float('chemical_delivered')->nullable();
            $table->float('chemical_end')->nullable();
            $table->integer('target_ppm')->nullable();
            $table->float('usage_rate')->nullable();
            $table->float('target_rate')->nullable();
            $table->float('min_rate')->nullable();
            $table->float('vendor_target')->nullable();
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
        Schema::drop('injections');
    }
}
