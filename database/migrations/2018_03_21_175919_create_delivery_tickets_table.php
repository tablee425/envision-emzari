<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->index();
            $table->integer('area_id')->index();
            $table->integer('salesrep_id')->index();     // this maps to user.id
            $table->enum('status', ['pending','approved','complete']);
            $table->string('ticket_number', 8);
            $table->date('purchase_date');
            $table->string('purchase_order_number');
            $table->string('ordered_by');
            $table->string('delivered_by');
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
        Schema::drop('delivery_tickets');
    }
}
