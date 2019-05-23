<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePurchaseOrderNullableOnDeliveryTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_tickets', function (Blueprint $table) {
            $table->string('purchase_order_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_tickets', function (Blueprint $table) {
            $table->string('purchase_order_number')->change();
        });
    }
}
