<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTicketItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_ticket_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_ticket_id')->index();
            $table->integer('location_id')->index();
            $table->string('chemical', 40);
            $table->enum('injection_type', ['continuous','batch']);
            $table->integer('quantity');
            $table->enum('packaging', ['drum', 'pail', 'tote', 'bulk', 'jug']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('delivery_ticket_items');
    }
}
